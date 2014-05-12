<?php
namespace jf;
class ExtendedUserErrors
{
	const Inactive=1;
	const NotFound=2;
	const Locked=3;
	const InvalidCredentials=4;
	const PasswordExpired=5;
	const TemporaryValidPassword=6;
}
/**
 * This is an extended user manager with support for activation, locking, reset password and etc.
 * @author abiusx
 * @version 2.0
 */
class ExtendedUserManager extends UserManager
{
	public static $LockCount=10;
	public static $LockTime=3600;
	public static $TemporaryPasswordTime=3600;
	public static $PasswordLifeTime=2592000; # one month


	/**
	 *
	 * @var ExtendedUserErrors
	 */
	public $LastError=null;
	/**
	 * Return the last error converted to string
	 * use the variable form if you need the constant.
	 * consumes the last error
	 * @return string
	 */
	function LastError()
	{
		if ($this->LastError===null)
			return null;
        $constantsClass = new \ReflectionClass ( '\jf\ExtendedUserErrors' );
        $consts=$constantsClass->getConstants();
        $res=array_search($this->LastError,$consts);
		$this->LastError=null;
		return $res;
	}

	/**
	 * Extends a normal user to extended user
	 * @param integer $UserID
	 * @param string $Email valid email address
	 * @return boolean true on success, false on already extended, null if not found
	 */
	function Extend($UserID,$Email)
	{
		if (!jf::$User->UserIDExists($UserID)) return null;
		if ($this->UserIDExists($UserID)) return false;
		$IID=jf::SQL("INSERT INTO {$this->TablePrefix()}xuser (ID,Email,CreateTimestamp,PasswordChangeTimestamp) VALUES (?,?,?,?)"
				,$UserID,$Email,jf::time(),jf::time()+self::$PasswordLifeTime);
		return $IID>0;
	}
	/**
	 * Attempt to login an extended user
	 * if user is only normal, it will return notfound error
	 * @param $Username
	 * @param $Password
	 * @return boolean true on success, false on failure
	 */
	function Login($Username,$Password)
	{
		$this->LastError=null;
		if (!$this->UserExists($Username))
		{
			$this->LastError=ExtendedUserErrors::NotFound;
			return false;
		}

		$UserID=$this->UserID($Username);
		$Info=$this->UserInfo($UserID);
		if ($Info['CreateTimestamp']==0)
			$Info=$this->InitUser($UserID);
		if ($Info['Activated']==0) #check activation
		{
			$this->LastError=ExtendedUserErrors::Inactive;
			return false;
		}
		if ($Info['LockTimeout']>jf::time()) #check if still locked
		{
			$this->LastError=ExtendedUserErrors::Locked;
			return false;
		}
		if ($Info['TemporaryResetPassword']==$Password and $Info['TemporaryResetPasswordTimeout']>jf::time()) #check temporary pass
		{
			$this->LastError=ExtendedUserErrors::TemporaryValidPassword;
			return false;
		}
		$R=jf::$User->ValidateUserCredentials($Username,$Password); #check credentials
		if (!$R)
		{
			$this->IncreaseFailedLoginAttempts($UserID);
			if ($Info['FailedLoginAttempts']+1>=self::$LockCount) #lock if too many attempts
			{
				$this->LastError=ExtendedUserErrors::Locked;
				$this->Lock($UserID);
				return false;
			}
			$this->LastError=ExtendedUserErrors::InvalidCredentials;
			return false;
		}
		else //valid credentials
		{
			if ($Info['PasswordChangeTimestamp']<jf::time()) #check password expiry
			{
				$this->LastError=ExtendedUserErrors::PasswordExpired;
				return false;
			}

			$this->Reset($UserID);
			return jf::$User->Login($Username, $Password);
		}
	}

	/**
	 * Reset a user by setting its timeouts, failed attempts, last login and temporary password
	 * @param integer $UserID
	 */
	public function Reset($UserID)
	{
		jf::SQL("UPDATE {$this->TablePrefix()}xuser SET FailedLoginAttempts=0 , LockTimeout=? , LastLoginTimestamp=? , TemporaryResetPasswordTimeout=? WHERE ID=? LIMIT 1",jf::time(),jf::time(),jf::time(),$UserID);

	}
	/**
	 * Logs in a user without providing password
	 * @param integer $UserID
	 * @return Boolean
	 */
	function ForceLogin($UserID)
	{
		$res=jf::$User->ForceLogin($UserID);
		if ($res)
		{
			$this->Reset($UserID);
			return true;
		}
		else
			return false;
	}

	/**
	 * Locks an extended user
	 * @param $UserID
	 */
	function Lock($UserID)
	{
		jf::SQL("UPDATE {$this->TablePrefix()}xuser SET LockTimeout=? , FailedLoginAttempts=0 WHERE ID=? LIMIT 1",jf::time()+self::$LockTime,$UserID);
	}
	/**
	 * Unlocks an extended user
	 * @param $UserID
	 * @return boolean
	 */
	function Unlock($UserID)
	{
		return jf::SQL("UPDATE {$this->TablePrefix()}xuser SET LockTimeout=? , FailedLoginAttempts=0 WHERE ID=? LIMIT 1",jf::time()-1,$UserID)>=1;
	}
	/**
	 * Increases user failed login attempts
	 * @param integer $UserID
	 */
	protected function IncreaseFailedLoginAttempts($UserID)
	{
		jf::SQL("UPDATE {$this->TablePrefix()}xuser SET FailedLoginAttempts=FailedLoginAttempts+1 WHERE ID=? LIMIT 1",$UserID);
	}
	/**
	 * Tells whether a user is locked or not
	 * @param $UserID
	 * return Boolean
	 */
	function IsLocked($UserID)
	{
		$R=jf::SQL("SELECT LockTimeout FROM {$this->TablePrefix()}xuser WHERE ID=?",$UserID);
		if (!$R) return false;
		return $R[0]['LockTimeout']>jf::time();
	}

	/**
	 * Whether an extended user is activated or not
	 * @param integer $UserID
	 * @return boolean
	 */
	function IsActive($UserID)
	{
		$res=jf::SQL("SELECT Activated FROM {$this->TablePrefix()}xuser WHERE ID=?",$UserID);
		if ($res)
			return $res[0]['Activated']==1;
		else
			return false;
	}
	/**
    Edits an extended user
    @param String $OldUsername
    @param String $NewUsername
    @param String $NewPassword leave null to not change
    @param String $NewEmail leave null to not change
    @return null on old user doesn't exist, false on new user already exists,  true on success.
	 */
	function EditUser($OldUsername, $NewUsername, $NewPassword = null,$Email=null)
	{
		if (! $this->UserExists ( $OldUsername )) return null;
		if ($OldUsername != $NewUsername and $this->UserExists ( $NewUsername )) return false;
		$UserID=$this->UserID($OldUsername);
		$res=jf::$User->EditUser($OldUsername, $NewUsername,$NewPassword);
		if ($Email!==null)
			jf::SQL("UPDATE {$this->TablePrefix()}xuser SET Email=? WHERE ID=?",$Email,$UserID);
		if ($NewPassword) #update password lifetime
			jf::SQL("UPDATE {$this->TablePrefix()}xuser SET PasswordChangeTimestamp=? WHERE ID=?",jf::time()+self::$PasswordLifeTime,$UserID);
		return $res;
	}	/**
	 * Creates an extended user and returns the user id
	 * @param $Username
	 * @param $Password
	 * @param $Email
	 * @return UserID, null on failure (existing)
	 */
	function CreateUser($Username,$Password)
	{
		$Email=func_get_args();
		$Email=$Email[2];
		if ($Email===null)
			throw new \Exception("You have to provide valid email address.");
		if ($this->FindByEmail($Email)) return null;
		$IID=jf::$User->CreateUser($Username,$Password);
		if ($IID===null) return null;
		jf::SQL("INSERT INTO {$this->TablePrefix()}xuser (ID,Email,CreateTimestamp,PasswordChangeTimestamp) VALUES (?,?,?,?)",
			$IID,$Email,jf::time(),jf::time()+self::$PasswordLifeTime);
		return $IID;

	}
	/**
	 * Removes an extended user
	 * @param $UserIDorUsername
	 * @return boolean
	 */
	function DeleteUser($Username)
	{
		$UserID=$this->UserID($Username);
		if (!$UserID) return false;
		if (jf::$User->DeleteUser($Username))
		{
			$res=jf::SQL("DELETE FROM {$this->TablePrefix()}xuser WHERE ID=?",$UserID);
			return $res>=1;
		}
		return false;
	}
	/**
	 * Check whether a user exists or not in extended users
	 * @param $Username
	 * @return Boolean
	 */
	function UserExists($Username)
	{
		$UserID=$this->UserID($Username);
		return $this->UserIDExists($UserID);
	}

	/**
	 * Checks whether a user ID exists or not in extended users
	 * @see \jf\UserManager::UserIDExists()
	 */
	function UserIDExists($UserID)
	{
		$R=jf::SQL("SELECT XU.ID FROM {$this->TablePrefix()}xuser AS XU JOIN {$this->TablePrefix()}users AS U ON (U.ID=XU.ID) WHERE XU.ID=?",$UserID);
		if ($R) return true;
		else
			return false;
	}
	/**
	 * Returns an array of info with all fields of the extended user and the jFramework user
	 * @param integer $UserID
	 * @return Array|null
	 */
	function UserInfo($UserID)
	{
		$R=jf::SQL("SELECT * FROM {$this->TablePrefix()}xuser AS XU JOIN {$this->TablePrefix()}users AS U ON (U.ID=XU.ID) WHERE XU.ID=?",$UserID);
		if ($R) return $R[0];
		else return null;
	}
	/**
	 * returns the number of all extended users
	 * @return integer
	 */
	function UserCount()
	{
		$R=jf::SQL("SELECT COUNT(*) AS Result FROM {$this->TablePrefix()}xuser AS XU");
		return $R[0]['Result'];
	}

	/**
	 * Activate an extendeduser
	 * @param integer $UserID
	 * @return boolean true on success, false on already active
	 */
	function Activate($UserID)
	{
		$res=jf::SQL("UPDATE {$this->TablePrefix()}xuser SET Activated=1 WHERE ID=? LIMIT 1",$UserID);
		return $res==1;
	}
	/**
	 * Deactivate an extended user
	 * @param integer $UserID
	 * @return boolean true on success, false on already inactive
	 */
	function Deactivate($UserID)
	{
		$res=jf::SQL("UPDATE {$this->TablePrefix()}xuser SET Activated=0 WHERE ID=? LIMIT 1",$UserID);
		return $res==1;
	}



	/**
	 * Sets a temporary reset password for a user, with which the user can log in and change his/her password.
	 * @param integer $UserID
	 * @return string temporary password on success, null of failure
	 */
	function TemporaryResetPassword($UserID)
	{
		$TempPassword=Password::Generate();
		if (!$this->UserIDExists($UserID)) return false;
		$res=jf::SQL("UPDATE {$this->TablePrefix()}xuser SET TemporaryResetPassword=?, TemporaryResetPasswordTimeout=? WHERE ID=?"
				,$TempPassword,jf::time()+self::$TemporaryPasswordTime,$UserID);
		if ($res>=1)
			return $TempPassword;
		else
			return null;

	}

	/**
	 * Lock time in seconds
	 * @param string $Username
	 * @return integer|NULL lock time
	 */
	function LockTime($Username)
	{
		$UserID=$this->UserID($Username);
		if (!$UserID) return null;
		$Info=$this->UserInfo($UserID);
		return $Info['LockTimeout']-jf::time();
	}
	/**
	 * Initiate an extended user by setting initial times
	 * @param integer $UserID
	 * @return array user info
	 */
	function InitUser($UserID)
	{
		jf::SQL("UPDATE {$this->TablePrefix()}xuser SET CreateTimestamp=?,PasswordChangeTimestamp=?",jf::time(),jf::time()+self::$PasswordLifeTime);
		return $this->UserInfo($UserID);
	}

	/**
	 * Returns user ID by email
	 * @param string $Email
	 * @return integer|null UserID
	 */
	function FindByEmail($Email)
	{
		$res=jf::SQL("SELECT * FROM {$this->TablePrefix()}xuser WHERE LOWER(Email)=LOWER(?)",$Email);
		if ($res)
		return $res[0]['ID'];
		else
			return null;
	}
}

