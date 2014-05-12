<?php
/**
 * SettingManager class
 * Save and load settings on a scope of general, user or session.
 * @version 2.0
 * @author abiusx
 */
//Note: userID = 0 means general option
namespace jf;

class Timeout {
	const NEVER=2147483647;
	const SECOND=1;
	const MINUTE=60;
	const HOUR=3600;
	const DAY=86400;
	const WEEK=604800;
	const MONTH=2592000;
	const YEAR=31536000;
}
class SettingManager extends Model
{


	private $PreparedLoadStatement=array();
	private $PreparedSaveStatement=array();
	private $PreparedDeleteStatement=array();
	private $PreparedSweepStatement=array();

	private function dbIndex()
	{
		return \jf\DatabaseManager::$DefaultIndex;
	}
	/**
	 * internal function use by other services
	 * @param string $Name
	 * @param mixed $Value
	 * @param int $UserID
	 * @param int $Timeout
	 * @return boolean success
	 */
	protected function _Save($Name, $Value, $UserID = 0, $Timeout)
	{
		if($Timeout==Timeout::NEVER)
			$Datetime = $Timeout;
		else
			$Datetime = jf::time () + $Timeout;
		if (!isset($this->PreparedSaveStatement[$this->dbIndex()]) or $this->PreparedSaveStatement[$this->dbIndex()]===null)
	        $this->PreparedSaveStatement[$this->dbIndex()]=jf::db()->prepare( "REPLACE INTO {$this->TablePrefix()}options (Name,Value, UserID, Expiration) VALUES (?,?,?,?);");
	    $r=$this->PreparedSaveStatement[$this->dbIndex()]->execute( $Name, serialize ( $Value ), $UserID, $Datetime );
		$this->_Sweep ();
		return $r>=1;
	}
	/**
	 * internal function use by other services
	 * @param string $Name
	 * @param int $UserID
	 * @return boolean success
	 */
	protected function _Delete($Name, $UserID = 0)
	{
	    if (!isset($this->PreparedDeleteStatement[$this->dbIndex()]) or $this->PreparedDeleteStatement[$this->dbIndex()]===null)
	        $this->PreparedDeleteStatement[$this->dbIndex()]=jf::db()->prepare( "DELETE FROM {$this->TablePrefix()}options  WHERE UserID=? AND Name=?");
	    $r=$this->PreparedDeleteStatement[$this->dbIndex()]->execute($UserID, $Name);
	    return $r>=1;
	}
	/**
	 * Delete expired settings with a probability
	 * @param boolean $force run the sweep 100%
	 */
	function _Sweep($force=false)
	{
		if(!$force) if (rand ( 0, 1000 ) / 1000.0 > .1)
			return; //percentage of SweepRatio, don't always do this when called
	    if (!isset($this->PreparedSweepStatement[$this->dbIndex()]) or $this->PreparedSweepStatement[$this->dbIndex()]===null)
	        $this->PreparedSweepStatement[$this->dbIndex()]=jf::db()->prepare("DELETE FROM {$this->TablePrefix()}options WHERE Expiration<=?");
	    $this->PreparedSweepStatement[$this->dbIndex()]->execute(jf::time());
	}
	/**
	 * This function loads a setting
	 * It expects to get at least 2 parameters
	 * @param string $Name
	 * @param integer $UserID
	 * @return mixed loaded item
	 *
	 */
	private function _Load($Name, $UserID=0)
	{
		$this->_Sweep ();
		if (!isset($this->PreparedLoadStatement[$this->dbIndex()]) or $this->PreparedLoadStatement[$this->dbIndex()]===null)
			$this->PreparedLoadStatement[$this->dbIndex()]=jf::db()->prepare("SELECT * FROM {$this->TablePrefix()}options WHERE Name=? AND UserID=?");
		$this->PreparedLoadStatement[$this->dbIndex()]->execute($Name, $UserID);
		$Res=$this->PreparedLoadStatement[$this->dbIndex()]->fetchAll();
		if($Res===null)
			return null;
		else
			return unserialize($Res[0]['Value']);
	}
	/**
	 * Save general settings for application
	 * @param string $Name
	 * @param mixed $Value
	 * @param integer $Timeout
	 * @return boolean success
	 */
	function SaveGeneral($Name, $Value, $Timeout = Timeout::NEVER)
	{
		return $this->_Save ( $Name, $Value, 0, $Timeout );
	}
	/**
	 * Load general application settings
	 * @param string $Name
	 * @return Ambigous <AssociativeArray, NULL, mixed>
	 */
	function LoadGeneral($Name)
	{
		return $this->_Load ( $Name, 0 );
	}
	/**
	 * Delete general settings
	 * @param string $Name
	 */
	function DeleteGeneral($Name)
	{
		return $this->_Delete ( $Name, 0 );
	}
	/**
	 * Save setting for a user
	 * @param string $Name
	 * @param mixed $Value
	 * @param int $UserID
	 * @param int $Timeout
	 * @throws \Exception
	 * @return boolean success for saving data
	 */
	function SaveUser($Name, $Value,$UserID=null, $Timeout = Timeout::NEVER)
	{
		if ($UserID===null)
		{
			if (jf::CurrentUser() == null)
				throw new \Exception ( "Can not load user options without a logged in user." );
			else
				$UserID=jf::CurrentUser();
		}

		if ($Timeout===null ) $Timeout=TIMESTAMP_WEEK;

		return $this->_Save ( $Name, $Value, $UserID, $Timeout );
	}
	/**
	 * Loads an option from the database
	 * @param String $Name
	 * @param Integer $UserID
	 * @return String Value on success, null on failure
	 */
	function LoadUser($Name,$UserID=null)
	{
		if ($UserID===null)
		{
			if (jf::CurrentUser() == null)
				throw new \Exception ( "Can not load user options without a logged in user." );
			else
				$UserID=jf::CurrentUser();
		}
		return $this->_Load ( $Name, $UserID );

	}
	/**
	 * delete user settings
	 * @param string $Name
	 * @param int $UserID
	 * @throws \Exception
	 * @return boolean success
	 */
	function DeleteUser($Name,$UserID=null)
	{
		if ($UserID===null)
		{
			if (jf::CurrentUser()== null)
				throw new \Exception ( "Can not delete user options without a logged in user." );
			else
				$UserID=jf::CurrentUser();
		}
		return $this->_Delete ( $Name, $UserID );
	}
	/**
	 * delete all user settings
	 * @throws \Exception
	 * @return int, number of rows
	 */
	function DeleteAllUser($UserID=null)
	{
		if ($UserID===null)
		{
			if (jf::CurrentUser() == null)
				throw new \Exception ( "Can not load user options without a logged in user." );
			else
				$UserID=jf::CurrentUser();
		}
		$r=jf::SQL (  "DELETE FROM {$this->TablePrefix()}options WHERE UserID=?", $UserID );
		return $r;
	}
	/**
	 * save settings in session
	 * @param string $Name
	 * @param int $Value
	 * @param int $Timeout
	 * @return boolean success
	 */
	function SaveSession($Name,$Value,$Timeout = Timeout::NEVER)
	{
	    return $this->SaveGeneral(session_id()."_$Name",$Value,$Timeout);
	}
	/**
	 * Load setting stored in session
	 * @param string $Name
	 * @return mixed
	 */
	function LoadSession($Name)
	{
	     return $this->LoadGeneral(session_id()."_$Name");
	}
	/**
	 * Delete setting stored in session
	 * @param string $Name
	 * @return boolean
	 */
	function DeleteSession($Name)
	{
        return $this->DeleteGeneral(session_id()."_$Name");
	}
}
?>
