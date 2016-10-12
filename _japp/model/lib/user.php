<?php
namespace jf;

class MultipleLogin
{
	/**
	 * Do not allow multiple logins, fails when tried.
	 * @var integer
	 */
	const Reject=1;
	/**
	 * Allow multiple logins, but overwrite the last login
	 * @var integer
	 */
	const Overwrite=2;
	/**
	 * Allow multiple logins, and create separate sessions
	 * @var integer
	 */
	const Allowed=4;

}

/**
 * Manages jframework base users. A base user only has a username and a password.
 * For advanced users having login features and password recovery, use ExtendedUserManager
 * @author abiusx
 * @version 1.3
 */
class UserManager extends Model
{
	/**
    Removes a user form system users if exists
    @param Username of the user
    @return boolean
	 */
	function DeleteUser($Username)
	{
		return (jf::SQL ( "DELETE FROM {$this->TablePrefix()}users WHERE LOWER(Username)=LOWER(?)", $Username )>=1);
	}
	/**
	 * Tells whether or not a user is logged in
	 * @param integer|string $UserID or $SessionID
	 * @return Boolean
	 */
	function IsLoggedIn($UserID)
	{
			return $this->LoginCount($UserID)>=1;
	}
	/**
	 * Returns numbers of a user ID that are logged in
	 * @param integer $UserID
	 */
	function LoginCount($UserID)
	{
		$Result=jf::SQL("SELECT COUNT(*) AS Result FROM {$this->TablePrefix()}session WHERE UserID=?",$UserID);
		return $Result[0]['Result'];
	}

	/**
	 * Logs out the current user and rolls the session
	 * This would not logout all users in mutli-login mode
	 * @param $UserID
	 */
	function Logout($UserID=null)
	{
		if ($UserID===null)
			if (jf::CurrentUser()===null)
				return false;
			else
				$UserID=jf::CurrentUser();
		jf::SQL ( "UPDATE {$this->TablePrefix()}session SET UserID=0 WHERE UserID=? AND SessionID=?", $UserID,jf::$Session->SessionID() );
		jf::$Session->RollSession();
	}
	/**
	 * Logs out all instance of a user that are logged in.
	 * @param integer $UserID
	 */
	function LogoutAll($UserID=null)
	{
		if ($UserID===null)
			if (jf::CurrentUser()===null)
				return false;
			else
				$UserID=jf::CurrentUser();
		jf::SQL ( "UPDATE {$this->TablePrefix()}session SET UserID=0 WHERE UserID=?", $UserID );
		jf::$Session->RollSession();
	}



	/**
    Edits a user credentials
    @param String $OldUsername
    @param String $NewUsername
    @param String $NewPassword leave null to not change
    @return null on old user doesn't exist, false on new user already exists,  true on success.
	 */
	function EditUser($OldUsername, $NewUsername, $NewPassword = null)
	{
		if (! $this->UserExists ( $OldUsername )) return null;
		if ($OldUsername != $NewUsername and $this->UserExists ( $NewUsername )) return false;
		if ($NewPassword)
		{
			$HashedPass=new Password($NewUsername, $NewPassword);
			j::SQL ( "UPDATE {$this->TablePrefix()}users SET Username=?, Password=?, Salt=?, Protocol=? WHERE LOWER(Username)=LOWER(?)",
				 $NewUsername, $HashedPass->Password(),$HashedPass->Salt(),$HashedPass->Protocol(), $OldUsername);
		}
		else
		{
			j::SQL ( "UPDATE {$this->TablePrefix()}users SET Username=? WHERE LOWER(Username)=LOWER(?)", $NewUsername, $OldUsername );
		}
		return true;
	}
	/**
	Validates a user credentials
    @param Username of the user
    @param Password of the user
    @return boolean
	 */
	function ValidateUserCredentials($Username, $Password)
	{
		$Record=jf::SQL("SELECT * FROM {$this->TablePrefix()}users WHERE LOWER(Username)=LOWER(?)",$Username);
		if (!$Record) return false;
		$Record=$Record[0];
		return Password::Validate($Username, $Password, $Record['Password'], $Record['Salt'],$Record['Protocol']);

	}

	/**
	 * Logs a user in only by user ID without needing valid credentials. Intended for system use only.
	 * This is the core login function, it is called everytime a user is trying to log in
	 * @param integer $UserID
	 * @return boolean|null false if user not found, null on multiple login reject
	 */
	function ForceLogin($UserID)
	{
		/**
		 * 4 possiblilities
		 * Session not logged in, UserID not logged in
		 * Roll and login
		 * Session logged in, UserID not logged in
		 * Roll and change session to UserID
		 * Session not logged in, UserID logged in
		 * Roll and change session to UserID
		 * Session logged in, UserID logged in,
		 * Roll and change session to UserID
		 *
		 */
		if (! jf::$Session->IsLoggedIn() && ! $this->IsLoggedIn($UserID))
		{
			jf::$Session->RollSession();
			$r=jf::SQL ( "UPDATE {$this->TablePrefix()}session SET UserID=?,LoginDate=?,LastAccess=?,AccessCount=? WHERE SessionID=?", $UserID, jf::time (), jf::time (), 1, jf::$Session->SessionID());
			if ($r>0) jf::$Session->SetCurrentUser($UserID);
			return $r>0;
		}
		else
		{

			if (self::$MultipleLoginPolicy==MultipleLogin::Reject)
			{
				if ($this->IsLoggedIn($UserID))	//already logged in
					return null;
				else							//session has another user, change
				{
					jf::$Session->RollSession();
					$r=jf::SQL( "UPDATE {$this->TablePrefix()}session SET UserID=?,LoginDate=?,LastAccess=?,AccessCount=? WHERE SessionID=?", $UserID,  jf::time (), jf::time (), 1, jf::$Session->SessionID());
				}
			}
			elseif (self::$MultipleLoginPolicy==MultipleLogin::Overwrite)
			{
				$this->LogoutAll($UserID);
				$r=jf::SQL( "UPDATE {$this->TablePrefix()}session SET UserID=?,LoginDate=?,LastAccess=?,AccessCount=? WHERE SessionID=?", $UserID,  jf::time (), jf::time (), 1, jf::$Session->SessionID());
			}
			elseif (self::$MultipleLoginPolicy==MultipleLogin::Allowed)
			{

				jf::$Session->RollSession();
				$r=jf::SQL( "UPDATE {$this->TablePrefix()}session SET UserID=?,LoginDate=?,LastAccess=?,AccessCount=? WHERE SessionID=?", $UserID,  jf::time (), jf::time (), 1, jf::$Session->SessionID());
				if ($r==0) //same user
					$r=1;
			}
			else
				throw new \Exception("Unknown multiple login policy.");
			if ($r>0) jf::$Session->SetCurrentUser($UserID);
			return $r>0;
		}
	}


	/**
	 * One of the constants from MultipleLogin
	 * @var integer
	 */
	static $MultipleLoginPolicy=MultipleLogin::Allowed;
	/**
	Logs in a user if credentials are valid, and based on multipleLoginPolicy
    @param string $Username of the user
    @param string $Password textual password of the user
    @return boolean|null false on invalid credentials, null on multiple login reject
	 */
	function Login($Username, $Password)
	{
		$Result = $this->ValidateUserCredentials ( $Username, $Password );
		if (!$Result) return false;
		$UserID=$this->UserID($Username);
		$res=$this->ForceLogin($UserID);
		return $res;
	}

	/**
	Checks to see whether a user exists or not
    @param Username of the new user
    @return boolean
	 */
	function UserExists($Username)
	{
		$res=jf::SQL ( "SELECT * FROM {$this->TablePrefix()}users WHERE LOWER(Username)=LOWER(?)", $Username );
		return ($res!==null);
	}


	/**
	 * Checks wether a user ID is valid or not
	 * @param integer $UserID
	 * @return boolean
	 */
	function UserIDExists($UserID)
	{
		$res=jf::SQL ( "SELECT * FROM {$this->TablePrefix()}users WHERE ID=?", $UserID);
		return ($res!==null);
	}
	/**
    Creates a new user in the system
    @param Username of the new user
    @param Password of the new user
    @return integer UserID on success
               null on User Already Exists
	*/
	function CreateUser($Username, $Password)
	{
		$Result = $this->UserExists ( $Username );
		if ($Result) return null;
		$HashedPass=new Password($Username, $Password);
		$Result = jf::SQL ( "INSERT INTO {$this->TablePrefix()}users (Username,Password,Salt,Protocol,discriminator)
			VALUES (?,?,?,?,?)", $Username, $HashedPass->Password(), $HashedPass->Salt(), $HashedPass->Protocol(), '');
		return $Result;
	}


	/**
	 * returns Username of a user
	 *
	 * @param Integer $UserID
	 * @return String
	 */
	function Username($UserID=null)
	{
		if ($UserID===null)
			$UserID=jf::CurrentUser();
		$Result = jf::SQL ( "SELECT Username FROM {$this->TablePrefix()}users WHERE ID=?", $UserID );
		if ($Result)
			return $Result [0] ['Username'];
		else
			return null;
	}

	/**
	 *
	 * @param string $Username
	 * @return integer UserID null on not exists
	 */
	function UserID($Username)
	{
		$res=jf::SQL("SELECT ID FROM {$this->TablePrefix()}users WHERE LOWER(Username)=LOWER(?)",$Username);
		if ($res)
			return $res[0]['ID'];
		else
			return null;

	}

	/**
	 * Returns total number of users
	 * @return integer
	 */
	function UserCount()
	{
		$res=jf::SQL("SELECT COUNT(*) FROM {$this->TablePrefix()}users");
		return $res[0]['COUNT(*)'];
	}


}
