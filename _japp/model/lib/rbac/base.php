<?php

namespace jf;

/**
 * RBAC base class, it contains operations that are essentially the same for
 * permissions and roles
 * and is inherited by both
 * 
 * @author abiusx
 * @version 1.0
 */
abstract class BaseRBAC extends Model
{
	function RootID()
	{
		return 1;
	}
	
	/**
	 * Return type of current instance, e.g roles, permissions
	 *
	 * @return string
	 */
	abstract protected function Type();
	/**
	 * Adds a new role or permission
	 * Returns new entry's ID
	 *
	 * @param string $Title
	 *        	Title of the new entry
	 * @param integer $Description
	 *        	Description of the new entry
	 * @param integer $ParentID
	 *        	optional ID of the parent node in the hierarchy
	 * @return integer ID of the new entry
	 */
	function Add($Title, $Description, $ParentID = null)
	{
		if ($ParentID === null)
			$ParentID = $this->RootID ();
		return $this->{$this->Type ()}->InsertChildData ( array ("Title" => $Title, "Description" => $Description ), "ID=?", $ParentID );
	}
	/**
	 * Return count of the entity
	 *
	 * @return integer
	 */
	function Count()
	{
		$Res = jf::SQL ( "SELECT COUNT(*) FROM {$this->TablePrefix()}rbac_{$this->Type()}" );
		return $Res [0] ['COUNT(*)'];
	}
	
	/**
	 * Returns ID of a path
	 *
	 * @todo this has a limit of 1000 characters on $Path
	 * @param string $Path
	 *        	such as /role1/role2/role3 ( a single slash is root)
	 * @return integer NULL
	 */
	function PathID($Path)
	{
		$Path = "root" . $Path;
		if ($Path [strlen ( $Path ) - 1] == "/")
			$Path = substr ( $Path, 0, strlen ( $Path ) - 1 );
		$Parts = explode ( "/", $Path );
		$res = jf::SQL ( "SELECT node.ID,GROUP_CONCAT(parent.Title ORDER BY parent.Lft SEPARATOR '/' ) AS Path
				FROM {$this->TablePrefix()}rbac_{$this->Type()} AS node,
				{$this->TablePrefix()}rbac_{$this->Type()} AS parent
				WHERE node.Lft BETWEEN parent.Lft AND parent.Rght
				AND  node.Title=?
				GROUP BY node.ID
				HAVING Path = ?
				ORDER BY parent.Lft
				", $Parts [count ( $Parts ) - 1], $Path );
		if ($res)
			return $res [0] ['ID'];
		else
			return null;
			// TODO: make the below SQL work, so that 1024 limit is over
		
		$QueryBase = ("SELECT n0.ID  \nFROM {$this->TablePrefix()}rbac_{$this->Type()} AS n0");
		$QueryCondition = "\nWHERE 	n0.Title=?";
		
		for($i = 1; $i < count ( $Parts ); ++ $i)
		{
			$j = $i - 1;
			$QueryBase .= "\nJOIN 		{$this->TablePrefix()}rbac_{$this->Type()} AS n{$i} ON (n{$j}.Lft BETWEEN n{$i}.Lft+1 AND n{$i}.Rght)";
			$QueryCondition .= "\nAND 	n{$i}.Title=?";
			// Forcing middle elements
			$QueryBase .= "\nLEFT JOIN 	{$this->TablePrefix()}rbac_{$this->Type()} AS nn{$i} ON (nn{$i}.Lft BETWEEN n{$i}.Lft+1 AND n{$j}.Lft-1)";
			$QueryCondition .= "\nAND 	nn{$i}.Lft IS NULL";
		}
		$Query = $QueryBase . $QueryCondition;
		$PartsRev = array_reverse ( $Parts );
		array_unshift ( $PartsRev, $Query );
		
		print_ ( $PartsRev );
		$res = call_user_func_array ( "jf::SQL", $PartsRev );
		if ($res)
			return $res [0] ['ID'];
		else
			return null;
	}
	
	/**
	 * Returns ID belonging to a title, and the first one on that
	 *
	 * @param unknown_type $Title        	
	 */
	function TitleID($Title)
	{
		return $this->{$this->Type ()}->GetID ( "Title=?", $Title );
	}
	/**
	 * Return the whole record of a single entry (including Rght and Lft fields)
	 *
	 * @param integer $ID        	
	 */
	protected function GetRecord($ID)
	{
		$args = func_get_args ();
		return call_user_func_array ( array ($this->{$this->Type ()}, "GetRecord" ), $args );
	}
	/**
	 * Returns title of entity
	 *
	 * @param integer $ID        	
	 * @return string NULL
	 */
	function GetTitle($ID)
	{
		$r = $this->GetRecord ( "ID=?", $ID );
		if ($r)
			return $r ['Title'];
		else
			return null;
	}
	/**
	 * Return description of entity
	 *
	 * @param integer $ID        	
	 * @return string NULL
	 */
	function GetDescription($ID)
	{
		$r = $this->GetRecord ( "ID=?", $ID );
		if ($r)
			return $r ['Description'];
		else
			return null;
	}
	/**
	 * Adds a path and all its components.
	 * Will not replace or create siblings if a component exists.
	 *
	 * @param string $Path
	 *        	such as /some/role/some/where
	 * @param array $Descriptions
	 *        	array of descriptions (will add with empty description if not
	 *        	avialable)
	 * @return integer NULL components ID
	 */
	function AddPath($Path, array $Descriptions = null)
	{
		assert ( $Path [0] == "/" );
		
		$Path = substr ( $Path, 1 );
		$Parts = explode ( "/", $Path );
		$Parent = 1;
		$index = 0;
		$CurrentPath = "";
		foreach ( $Parts as $p )
		{
			if (isset ( $Descriptions [$index] ))
				$Description = $Descriptions [$index];
			else
				$Description = "";
			$CurrentPath .= "/{$p}";
			$t = $this->PathID ( $CurrentPath );
			if (! $t)
			{
				$IID = $this->Add ( $p, $Description, $Parent );
				$Parent = $IID;
			}
			else
			{
				$Parent = $t;
			}
		}
		return $Parent;
	}
	
	/**
	 * Edits an entity, changing title and/or description
	 *
	 * @param integer $ID        	
	 * @param string $NewTitle        	
	 * @param string $NewDescription        	
	 *
	 */
	function Edit($ID, $NewTitle = null, $NewDescription = null)
	{
		$Data = array ();
		if ($NewTitle !== null)
			$Data ['Title'] = $NewTitle;
		if ($NewDescription !== null)
			$Data ['Description'] = $NewDescription;
		return $this->{$this->Type ()}->EditData ( $Data, "ID=?", $ID ) == 1;
	}
	
	/**
	 * Returns children of an entity
	 *
	 * @return array
	 *
	 */
	function Children($ID)
	{
		return $this->{$this->Type ()}->ChildrenConditional ( "ID=?", $ID );
	}
	
	/**
	 * Returns descendants of a node, with their depths in integer
	 *
	 * @param integer $ID        	
	 * @return array with keys as titles and Title,ID, Depth and Description
	 *        
	 */
	function Descendants($ID)
	{
		$res = $this->{$this->Type ()}->DescendantsConditional(/* absolute depths*/false, "ID=?", $ID );
		$out = array ();
		if (is_array ( $res ))
			foreach ( $res as $v )
				$out [$v ['Title']] = $v;
		return $out;
	}
	
	/**
	 * Return depth of a node
	 *
	 * @param integer $ID        	
	 */
	function Depth($ID)
	{
		return $this->{$this->Type ()}->DepthConditional ( "ID=?", $ID );
	}
	
	/**
	 * Returns path of a node
	 *
	 * @param integer $ID        	
	 * @return string path
	 */
	function Path($ID)
	{
		$res = $this->{$this->Type ()}->PathConditional ( "ID=?", $ID );
		$out = null;
		if (is_array ( $res ))
			foreach ( $res as $r )
				if ($r ['ID'] == 1)
					$out = '/';
				else
					$out .= "/" . $r ['Title'];
		if (strlen ( $out ) > 1)
			return substr ( $out, 1 );
		else
			return $out;
	}
	
	/**
	 * Returns parent of a node
	 *
	 * @param integer $ID        	
	 * @return array including Title, Description and ID
	 *        
	 */
	function ParentNode($ID)
	{
		return $this->{$this->Type ()}->ParentNodeConditional ( "ID=?", $ID );
	}
	
	/**
	 * Reset the table back to its initial state
	 * Keep in mind that this will not touch relations
	 *
	 * @param boolean $Ensure
	 *        	must be true to work, otherwise error
	 * @throws \Exception
	 * @return integer number of deleted entries
	 *        
	 */
	function Reset($Ensure = false)
	{
		if ($Ensure !== true)
		{
			throw new \Exception ( "You must pass true to this function, otherwise it won't work." );
			return;
		}
		$res = jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_{$this->Type()}" );
		$Adapter = DatabaseManager::Configuration ()->Adapter;
		if ($Adapter == "mysqli" or $Adapter == "pdo_mysql")
			jf::SQL ( "ALTER TABLE {$this->TablePrefix()}rbac_{$this->Type()} AUTO_INCREMENT=1 " );
		elseif ($Adapter == "pdo_sqlite")
			jf::SQL ( "delete from sqlite_sequence where name=? ", $this->TablePrefix () . "rbac_{$this->Type()}" );
		else
			throw new \Exception ( "RBAC can not reset table on this type of database: {$Adapter}" );
		$iid = jf::SQL ( "INSERT INTO {$this->TablePrefix()}rbac_{$this->Type()} (Title,Description,Lft,Rght) VALUES (?,?,?,?)", "root", "root",0,1 );
		return $res;
	}
	

	/**
	 * Assigns a role to a permission (or vice-versa)
	 *
	 * @param integer $Role        	
	 * @param integer $Permission        	
	 * @return boolean inserted or existing
	 */
	function Assign($Role, $Permission)
	{
		return jf::SQL ( "INSERT INTO {$this->TablePrefix()}rbac_rolepermissions
			(RoleID,PermissionID,AssignmentDate)
			VALUES (?,?,?)", $Role, $Permission, jf::time () ) == 1;
	}
	/**
	 * Unassigns a role-permission relation
	 * 
	 * @param integer $Role        	
	 * @param integer $Permission        	
	 * @return boolean
	 */
	function Unassign($Role, $Permission)
	{
		return jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_rolepermissions WHERE
		RoleID=? AND PermissionID=?", $Role, $Permission ) == 1;
	}
	
	/**
	 * Remove all role-permission relations
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
		$res = jf::SQL ( "DELETE FROM {$this->TablePrefix()}rbac_rolepermissions" );
		
		$Adapter = DatabaseManager::Configuration ()->Adapter;
		if ($Adapter == "mysqli" or $Adapter == "pdo_mysql")
			jf::SQL ( "ALTER TABLE {$this->TablePrefix()}rbac_rolepermissions AUTO_INCREMENT =1 " );
		elseif ($Adapter == "pdo_sqlite")
			jf::SQL ( "delete from sqlite_sequence where name=? ", $this->TablePrefix () . "_rbac_rolepermissions" );
		else
			throw new \Exception ( "RBAC can not reset table on this type of database: {$Adapter}" );
		$this->Assign ( "root", "root" );
		return $res;
	}
}