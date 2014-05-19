<?php
/*
 * Role Based Access Control Implemented on NIST RBAC Standard Model level 2.
 * Hierarchical RBAC (Restricted Hierarchical RBAC : only trees of roles) by
 * AbiusX Features: Restricted Hierarchies for both Permissions and Roles.
 * Multiple roles for each user Optimized queries
 */


namespace jf;
class RBACException extends \Exception {
}
class RBACRoleNotFoundException extends RBACException {}
class RBACPermissionNotFoundException extends RBACException {}
class RBACUserNotFoundException extends RBACException {}



jf::import("jf/model/lib/rbac/base");
jf::import("jf/model/lib/rbac/roles");
jf::import("jf/model/lib/rbac/permissions");
jf::import("jf/model/lib/rbac/users");

/**
 * RBACManager class, provides NIST Level 2 Standard Hierarchical Role Based Access Control
 * Has three members, Roles, Users and Permissions for specific operations
 * @author abiusx
 * @version 1.0
 */
class RBACManager extends Model
{
	function __construct()
	{
		$this->Users = new RBACUserManager ();
		$this->Roles = new RoleManager ();
		$this->Permissions = new PermissionManager ();
	}
	/**
	 *
	 * @var \jf\PermissionManager
	 */
	public $Permissions;
	/**
	 *
	 * @var \jf\RoleManager
	 */
	public $Roles;
	/**
	 *
	 * @var \jf\RBACUserManager
	 */
	public $Users;



	/**
	 * Assign a role to a permission. Alias for what's in the base class
	 * @param string|integer $Role path or string title or integer id
	 * @param string|integer $Permission path or string title or integer id
	 * @return boolean
	 */
	function Assign($Role,$Permission)
	{
		if (is_int ( $Permission ))
		{
			$PermissionID = $Permission;
		}
		else
		{
			if (substr($Permission,0,1)=="/")
				$PermissionID=$this->Permissions->PathID($Permission);
			else
				$PermissionID=$this->Permissions->TitleID($Permission);
		}
		if (is_int ( $Role ))
		{
			$RoleID = $Role;
		}
		else
		{
			if (substr($Role,0,1)=="/")
				$RoleID=$this->Roles->PathID($Role);
			else
				$RoleID=$this->Roles->TitleID($Role);
		}


		return $this->Roles->Assign($RoleID, $PermissionID);
	}


	/**
	 * Prepared statement for check query
	 * @var BaseDatabaseStatement
	 */
	private $ps_Check = null;
	/**
	 * Checks whether a user has a permission or not.
	 *
	 * @param string|integer $Permission you can provide a path like /some/permission, a title, or the permission ID.
	 * 					in case of ID, don't forget to provide integer (not a string containing a number)
	 * @param string|integer $User optional, username or ID of user or null which evaluates current user
	 * @throws RBACPermissionNotFoundException
	 * @throws RBACUserNotFoundException
	 * @return boolean
	 */

	function Check($Permission, $UserID = null)
	{
		//convert permission and user to ID
		if (is_int ( $Permission ))
		{
			$PermissionID = $Permission;
		}
		else
		{
			if (substr($Permission,0,1)=="/")
				$PermissionID=$this->Permissions->PathID($Permission);
			else
				$PermissionID=$this->Permissions->TitleID($Permission);
		}
		if ($UserID === null)
			$UserID = jf::CurrentUser ();
		elseif (!is_int($UserID))
			$UserID=jf::$User->UserID($UserID);

		//if invalid, throw exception
		if ($PermissionID===null)
			throw new RBACPermissionNotFoundException("The permission '{$Permission}' not found.");
		if ($UserID===null)
			throw new RBACUserNotFoundException("The user '{$UserID}' provided to RBAC::Check function not found.");

		if ($this->ps_Check===null)
		{
			$this->ps_Check= jf::db ()->prepare ( "SELECT COUNT(*) AS Result
			FROM
				{$this->TablePrefix()}rbac_userroles AS TUrel

				JOIN {$this->TablePrefix()}rbac_roles AS TRdirect ON (TRdirect.ID=TUrel.RoleID)
				JOIN {$this->TablePrefix()}rbac_roles AS TR ON ( TR.Lft BETWEEN TRdirect.Lft AND TRdirect.Rght)
				/* we join direct roles with indirect roles to have all descendants of direct roles */
				JOIN
				(	{$this->TablePrefix()}rbac_permissions AS TPdirect
				JOIN {$this->TablePrefix()}rbac_permissions AS TP ON ( TPdirect.Lft BETWEEN TP.Lft AND TP.Rght)
				/* direct and indirect permissions */
				JOIN {$this->TablePrefix()}rbac_rolepermissions AS TRel ON (TP.ID=TRel.PermissionID)
				/* joined with role/permissions on roles that are in relation with these permissions*/
				) ON ( TR.ID = TRel.RoleID)
				WHERE
				/* TU.ID=? */
				TUrel.UserID=?
				AND
				TPdirect.ID=?
			" );
		}
		$this->ps_Check->execute ( $UserID, $PermissionID );
		$Res = $this->ps_Check->fetchAll ();

		return $Res [0] ['Result']>=1;
	}
	/**
	 * Enforce a permission on current user
	 * @param string|integer $Permission path or title or ID of permission
	 */
	function Enforce($Permission)
	{
		if (jf::CurrentUser()===null)
		{
			jf::run ( "view/_internal/error/401", array ("Permission" => $Permission ) );
			exit();
		}
		if (! $this->Check ( $Permission ))
		{
			jf::run ( "view/_internal/error/403", array ("Permission" => $Permission ) );
			exit ();
		}
	}

	/**
	 * Remove all roles, permissions and assignments
	 * mostly used for testing
	 *
	 * @param boolean $Ensure
	 *        	must set or throws error
	 * @return boolean
	 */
	function Reset($Ensure = false)
	{
		if ($Ensure !== true)
		{
			throw new \Exception ( "You must pass true to this function, otherwise it won't work." );
			return;
		}
		$res=true;
		$res=$res and $this->Roles->ResetAssignments(True);
		$res=$res and $this->Roles->Reset(true);
		$res=$res and $this->Permissions->Reset(True);
		$res=$res and $this->Users->ResetAssignments(True);
		return $res;
	}
}