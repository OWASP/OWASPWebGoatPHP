<?php

namespace jf;

/**
 * jFramework PDO_SQLite 3.0 adapter
 * @author abiusx
 * @version 3.00
 */
class DB_pdo_sqlite extends BaseDatabase
{
	/**
	 * the actual DB object
	 * @var PDO
	 */
	public $DB;
	protected $m_databasename;
	
	function __construct(DatabaseSetting $db)
	{
		parent::__construct($db);
		if ($db->Username and $db->Username != "")
		{
			$File = "{$db->DatabaseName}";
			$this->DB = new \PDO ( "sqlite::memory:" );
			$this->DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
			$this->Initialize($db->DatabaseName);
// 			if (! is_writable ( $File ) && (! defined ( "jf_DB_ReadOnly" ) or ! constant ( "jf_DB_ReadOnly" ))) trigger_error ( "PDO_SQLite : database file not writable. Set read-only or grant write access to database file." );
// 			if (! is_writable(constant ( "jf_DB_SQLite_Folder" )) && (!defined( "jf_DB_ReadOnly" ) or ! constant ( "jf_DB_ReadOnly" ))) trigger_error("PDO_SQLite : the folder containing the database should have full permissions, or set connection to read-only.");
		}
		else
			$this->DB = null; //this is mandatory for no-database jFramework
		$this->m_databasename = $db->DatabaseName;
	}

	function __destruct()
	{
		if ($this->DB) $this->DB = null; //destroys the PDO object
	}

	function LastID()
	{
		return $this->DB->lastInsertId ();
	}

	function quote($Param)
	{
		$args = func_get_args ();
		if (count($args)>1)
		{
			foreach ( $args as &$arg )
				if ($x = $this->DB->quote ( $arg )) $arg = $x;
		}
		else
			return $this->DB->quote($args[0]);	
	}

	function query($QueryString)
	{
		if (! $this->DB) return null;
		$this->QueryCount += 1;
		return $this->DB->query ( $QueryString );
	}

	function exec($Query)
	{
		if (! $this->DB) return null;
		$this->QueryCount += 1;
		return $this->DB->exec($Query);
	}

	function PrepareStatement($Query)
	{
		return new DB_Statement_pdo_sqlite($this, $Query);
	}
	
	public function ListTables($All=false)
	{
		$TablesQuery = $this->SQL ( "SELECT name FROM sqlite_master WHERE type = 'table'" );
		$out = array ();
		if (is_array ( $TablesQuery ))
		{
			foreach ( $TablesQuery as $t )
			{
				$prefix=substr($t ['name'], 0, strlen($this->Config->TablePrefix));
				if ($All or $prefix == $this->Config->TablePrefix)
					$out [] = $t ['name'];
			}
		}
		$this->ListOfTable = true;
		return $out;
	}
	
	function DropTables()
	{
		$tables = $this->ListTables ();
		array_shift($tables);
		if (is_array ( $tables ))
			foreach ( $tables as $tableName )
				$this->SQL ( "DROP TABLE " . $tableName );
	}
	
	function TruncateTables()
	{
		$tables = $this->ListTables ();
		array_shift($tables);
		if (is_array ( $tables ))
			foreach ( $tables as $tableName )
				$this->SQL ( "DELETE FROM " . $tableName );
	}
}

/**
 * 
 * jFramework PDO_SQLite statement 
 * @author abiusx
 * @version 2.0
 */
class DB_Statement_pdo_sqlite extends BaseDatabaseStatement
{
	/**
	 * DBAL
	 *
	 * @var DB_pdo_sqlite
	 */
	private $DBAL;
	/**
	 * used for debugging
	 * @var string
	 */
	private $_query;
	/**
	 * used for debugging
	 * @var array
	 */
	private $_params;

	function __construct(DB_pdo_sqlite $DB,$Query)
	{
		$this->DBAL = $DB;
		$this->Statement=$DB->DB->prepare($Query);
		$this->_query=$Query;
	}
	/**
	 * Enter description here...
	 *
	 * @var PDOStatement
	 */
	private $Statement;
	
	function __destruct()
	{
		if ($this->Statement) 
			$this->Statement = null;
	}
	
	/**
	 * Binds a few values to a prepared statement
	 *
	 */
	function bindAll()
	{
		$args = func_get_args ();
		$i = 0;
		foreach ( $args as &$arg )
			$this->Statement->bindValue ( ++ $i, $arg );
	}

	/**
	 * Executes the prepared statement using binded values. if you provide this function with
	 * arguments, Then those would be binded as well.
	 *
	 */
	function execute()
	{
		if (func_num_args() >= 1)
		{
			$args = func_get_args();
			call_user_func_array ( array (
				$this, "bindAll" 
			), $args );
		}
		$this->DBAL->increaseQueryCount();
		$args=array_merge(array($this->_query),func_get_args());
		$this->DBAL->QueryStart($args);
		$r=$this->Statement->execute();
		$this->DBAL->QueryEnd();
		return $r;
	
	}

	function rowCount()
	{
		return $this->Statement->rowCount ();
	}

	function fetch()
	{
		return $this->Statement->fetch ( \PDO::FETCH_ASSOC );
	}
}
?>