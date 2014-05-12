<?php

namespace jf;

/**
 * RBAC Role Manager
 * it has specific functions to the roles
 *
 * @author abiusx
 * @version 1.0
 */
class RoleManager extends BaseRBAC
{
	/**
	 * Roles Nested Set
	 *
	 * @var FullNestedSet
	 */
	protected $roles = null;
	protected function Type()
	{
		return "roles";
	}
	function __construct()
	{
		$this->Type = "roles";
		$this->roles = new FullNestedSet ( "rbac_roles", "ID", "Lft", "Rght" );
	}

	/**
	 * Remove a role from system
	 *
	 * @param integer $ID
	 *        	role id
	 * @param boolean $Recursive
	 *        	delete all descendants
	 *
	 */
	function Remove($ID, $Recursive = false)
	{
		$this->UnassignPermissions ( $ID );
		$this->UnassignUsers ( $ID );
		if (! $Recursive)
			return $this->roles->DeleteConditional ( "ID=?", $ID );
		else
			return $this->roles->DeleteSubtreeConditional ( "ID=?", $ID );
	}
	/**
	 * Unassigns all permissions belonging to a role
	 *
	 * @param integer $ID
	 *        	role ID
	 * @return integer number of assignments deleted
	 */
	function UnassignPermissions($ID)
	{
		$r = jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_rolepermissions WHERE
		RoleID=? ", $ID );
		return $r;
	}
	/**
	 * Unassign all users that have a certain role
	 *
	 * @param integer $ID
	 *        	role ID
	 * @return integer number of deleted assignments
	 */
	function UnassignUsers($ID)
	{
		return jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_userroles WHERE
	RoleID=?", $ID );
	}
	

	/**
	 * Checks to see if a role has a permission or not
	 *
	 * @param integer $Role
	 *        	ID
	 * @param integer $Permission
	 *        	ID
	 * @return boolean
	 */
	function HasPermission($Role, $Permission)
	{
		$Res = jf::SQL ( "
		SELECT COUNT(*) AS Result
		FROM {$this->TablePrefix()}rbac_rolepermissions AS TRel
		JOIN {$this->TablePrefix()}rbac_permissions AS TP ON ( TP.ID= TRel.PermissionID)
		JOIN {$this->TablePrefix()}rbac_roles AS TR ON ( TR.ID = TRel.RoleID)
		WHERE TR.Lft BETWEEN
		(SELECT Lft FROM {$this->TablePrefix()}rbac_roles WHERE ID=?)
		AND
		(SELECT Rght FROM {$this->TablePrefix()}rbac_roles WHERE ID=?)
		/* the above section means any row that is a descendants of our role (if descendant roles have some permission, then our role has it two) */
		AND TP.ID IN (
		SELECT parent.ID
		FROM {$this->TablePrefix()}rbac_permissions AS node,
		{$this->TablePrefix()}rbac_permissions AS parent
		WHERE node.Lft BETWEEN parent.Lft AND parent.Rght
		AND ( node.ID=? )
		ORDER BY parent.Lft
		);
		/*
		the above section returns all the parents of (the path to) our permission, so if one of our role or its descendants
		has an assignment to any of them, we're good.
		*/
		", $Role, $Role, $Permission );
		return $Res [0] ['Result'] >= 1;
	}
	/**
	 * Returns all permissions assigned to a role
	 *
	 * @param integer $Role
	 *        	ID
	 * @param boolean $OnlyIDs
	 *        	if true, result would be a 1D array of IDs
	 * @return Array 2D or 1D or null
	 *         the two dimensional array would have ID,Title and Description of
	 *         permissions
	 */
	function Permissions($Role, $OnlyIDs = true)
	{
		if ($OnlyIDs)
		{
			$Res = jf::SQL ( "SELECT PermissionID AS `ID` FROM {$this->TablePrefix()}rbac_rolepermissions WHERE RoleID=? ORDER BY PermissionID", $Role );
			if (is_array ( $Res ))
			{
				$out = array ();
				foreach ( $Res as $R )
					$out [] = $R ['ID'];
				return $out;
			}
			else
				return null;
		}
		else
			return jf::SQL ( "SELECT `TP`.* FROM {$this->TablePrefix()}rbac_rolepermissions AS `TR`
		RIGHT JOIN {$this->TablePrefix()}rbac_permissions AS `TP` ON (`TR`.PermissionID=`TP`.ID)
		WHERE RoleID=? ORDER BY TP.PermissionID", $Role );
	}
}