<?php
namespace jf;
/**
 * RBAC User Manager
 * holds specific operations for users
 * @author abiusx
 * @version 1.0
 */
class RBACUserManager extends Model
{
	/**
	 * Checks to see whether a user has a role or not
	 *
	 * @param integer|string $Role id, title or path
	 * @param integer $User ID optional
	 * @return boolean success
	 */
	function HasRole($Role, $User = null)
	{
		if (is_int ( $Role ))
		{
			$RoleID = $Role;
		}
		else
		{
			if (substr($Role,0,1)=="/")
				$RoleID=jf::$RBAC->Roles->PathID($Role);
			else
				$RoleID=jf::$RBAC->Roles->TitleID($Role);
		}
		
		if ($User === null)
			$User = jf::CurrentUser ();
		$R = jf::SQL ( "SELECT * FROM {$this->TablePrefix()}rbac_userroles AS TUR 
			JOIN {$this->TablePrefix()}rbac_roles AS TRdirect ON (TRdirect.ID=TUR.RoleID)
			JOIN {$this->TablePrefix()}rbac_roles AS TR ON (TR.Lft BETWEEN TRdirect.Lft AND TRdirect.Rght)
		
		WHERE
		TUR.UserID=? AND TR.ID=?", $User, $RoleID );
		return $R !== null;
	}
	/**
	 * Assigns a role to a user
	 *
	 * @param integer|string $Role id or path or title
	 * @param integer $UserID
	 *        	ID
	 *        	optional, UserID or the current user would be used (use 0 for
	 *        	guest)
	 * @return inserted or existing
	 */
	function Assign($Role, $UserID = null)
	{
		if ($UserID === null)
			$UserID = jf::CurrentUser ();
		if (is_int ( $Role ))
		{
			$RoleID = $Role;
		}
		else
		{
			if (substr($Role,0,1)=="/")
				$RoleID=jf::$RBAC->Roles->PathID($Role);
			else
				$RoleID=jf::$RBAC->Roles->TitleID($Role);
		}		
		$res = jf::SQL ( "INSERT INTO {$this->TablePrefix()}rbac_userroles
		(UserID,RoleID,AssignmentDate)
		VALUES (?,?,?)
		", $UserID, $RoleID, jf::time () );
		return $res >= 1;
	}
	/**
	 * Unassigns a role from a user
	 *
	 * @param integer $Role
	 *        	ID
	 * @param integer $UserID
	 *        	optional, UserID or the current user would be used (use 0 for
	 *        	guest)
	 * @return boolean success
	 */
	function Unassign($Role, $UserID = null)
	{
		if ($UserID === null)
			$UserID = jf::CurrentUser ();
		return jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_userroles
		WHERE UserID=? AND RoleID=?", $UserID, $Role ) >= 1;
	}
	
	/**
	 * Returns all roles of a user
	 * 
	 * @param integer $UserID
	 *        	optional
	 * @return array|null
	 */
	function AllRoles($UserID)
	{
		if ($UserID === null)
			$UserID = jf::CurrentUser ();
		
		return jf::SQL ( "SELECT TR.*
			FROM
			{$this->TablePrefix()}rbac_userroles AS `TRel`
			JOIN {$this->TablePrefix()}rbac_roles AS `TR` ON
			(`TRel`.RoleID=`TR`.ID)
			WHERE TRel.UserID=?", $UserID );
	}
	/**
	 * Return count of roles for a user
	 * 
	 * @param integer $UserID
	 *        	optional
	 * @return integer
	 */
	function RoleCount($UserID = null)
	{
		if ($UserID === null)
			$UserID = jf::CurrentUser ();
		$Res = jf::SQL ( "SELECT COUNT(*) AS Result FROM {$this->TablePrefix()}rbac_userroles WHERE UserID=?",$UserID );
		return $Res [0] ['Result'];
	}
	
	/**
	 * Remove all role-user relations
	 * mostly used for testing
	 *
	 * @param boolean $Ensure
	 *        	must set or throws error
	 * @return number of deleted relations
	 */
	function ResetAssignments($Ensure = false)
	{
		if ($Ensure !== true)
		{
			throw new \Exception ( "You must pass true to this function, otherwise it won't work." );
			return;
		}
		$res = jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_userroles" );
	
		$Adapter = DatabaseManager::Configuration ()->Adapter;
		if ($Adapter == "mysqli" or $Adapter == "pdo_mysql")
			jf::SQL ( "ALTER TABLE {$this->TablePrefix()}rbac_userroles AUTO_INCREMENT =1 " );
		elseif ($Adapter == "pdo_sqlite")
		jf::SQL ( "delete from sqlite_sequence where name=? ", $this->TablePrefix () . "_rbac_userroles" );
		else
			throw new \Exception ( "RBAC can not reset table on this type of database: {$Adapter}" );
		$this->Assign ( "root", 1);
		return $res;
	}
}