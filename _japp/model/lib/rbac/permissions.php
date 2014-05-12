<?php

namespace jf;

/**
 * RBAC Permission Manager
 * holds specific operations for permissions
 *  
 * @author abiusx
 * @version 1.0
 */
class PermissionManager extends BaseRBAC
{
	/**
	 * Permissions Nested Set
	 *
	 * @var FullNestedSet
	 */
	protected $permissions;
	protected function Type()
	{
		return "permissions";
	}
	function __construct()
	{
		$this->permissions = new FullNestedSet ( "rbac_permissions", "ID", "Lft", "Rght" );
	}
	/**             
	 * Remove a permission from system
	 *
	 * @param integer $ID
	 *        	permission id
	 * @param boolean $Recursive
	 *        	delete all descendants
	 *        	
	 */
	function Remove($ID, $Recursive = false)
	{
		$this->UnassignRoles ( $ID );
		if (! $Recursive)
			return $this->permissions->DeleteConditional ( "ID=?", $ID );
		else
			return $this->permissions->DeleteSubtreeConditional ( "ID=?", $ID );
	}
	
	/**
	 * Unassignes all roles of this permission, and returns their number
	 *
	 * @param integer $ID        	
	 * @return integer
	 */
	function UnassignRoles($ID)
	{
		$res = jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_rolepermissions WHERE
		PermissionID=?", $ID );
		return $res;
	}
	
	/**
	 * Returns all roles assigned to a permission
	 *
	 * @param integer $Permission
	 *        	ID
	 * @param boolean $OnlyIDs
	 *        	if true, result would be a 1D array of IDs
	 * @return Array 2D or 1D or null
	 */
	function Roles($Permission, $OnlyIDs = true)
	{
		if (! is_numeric ( $Permission ))
			$Permission = $this->Permission_ID ( $Permission );
		if ($OnlyIDs)
		{
			$Res = jf::SQL ( "SELECT RoleID AS `ID` FROM
			{$this->TablePrefix()}rbac_rolepermissions WHERE PermissionID=? ORDER BY RoleID", $Permission );
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
			RIGHT JOIN {$this->TablePrefix()}rbac_roles AS `TP` ON (`TR`.RoleID=`TP`.ID)
			WHERE PermissionID=? ORDER BY TP.RoleID", $Permission );
	}
}