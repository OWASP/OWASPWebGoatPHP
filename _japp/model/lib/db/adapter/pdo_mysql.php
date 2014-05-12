<?php
namespace jf;
/**
 * jFramework PDO_MySQL driver
 * recommended for systems where MySQLi is not installed or not working properly
 * @author abiusx
 * @version 1.03
 */
class DB_pdo_mysql extends BaseDatabase
{
	/**
	 * the actual DB object
	 * @var PDO
	 */
	public $DB;
	
	/**
	 * Debug mode. if set to true DBAL is intended to generate debug output
	 * @var boolean
	 */
	public $Debug=false;
	
	protected  $m_databasename;
	function __construct(DatabaseSetting $db)
	{
		parent::__construct($db);
		if ($db->Username and $db->Username != "")
		{
			$this->DB = new \PDO ( "mysql:dbname={$db->DatabaseName};host={$db->Host};",$db->Username,$db->Password);
			$this->DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
			$this->Initialize($db->DatabaseName);
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
		$res=$this->DB->lastInsertId ();
		return $res;
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
		return new DB_Statement_pdo_mysql($this, $Query);
	}
}


/**
 * jFramework DBAL's PDO_MySQL prepared statements class
 * @author abiusx
 * @version 1.00
 */
class DB_Statement_pdo_mysql extends BaseDatabaseStatement
{
	/**
	 * DBAL
	 *
	 * @var DB_pdo_mysql
	 */
	private $DBAL;
	/**
	 * Enter description here...
	 *
	 * @var PDOStatement
	 */
	private $Statement;
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
	
	function __construct(DB_pdo_mysql $DB,$Query)
	{
		$this->DBAL = $DB;
		$this->Statement=$DB->DB->prepare($Query);
		$this->_query=$Query;
	}

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
		$this->_params=$args;
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
		if (func_num_args () >= 1)
		{
			$args = func_get_args ();
			call_user_func_array ( array (
				$this, "bindAll" 
			), $args );
		}
		$this->DBAL->increaseQueryCount();
		$args=array_merge(array($this->_query),func_get_args());
		$this->DBAL->QueryStart ($args);
		$r=$this->Statement->execute ();
		$this->DBAL->QueryEnd ();
		return $r;
	
	}

	function rowCount()
	{
		$res=$this->Statement->rowCount ();
		return $res;
	}

	function fetch()
	{
		return $this->Statement->fetch ( \PDO::FETCH_ASSOC );
	}
}
?>