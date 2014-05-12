<?php
namespace jf;
class trait_DatabaseProfiler {
	/**
	 * Converts a query in prepared statements format with all its parameters to
	 * a string
	 *
	 * @param string $Query
	 * @param string $Rest
	 *        	optional
	 * @return string
	 */
	protected function QueryToString($args)
	{
		$Query = array_shift ( $args );
		if (is_array ( $args ))
			foreach ( $args as $arg )
			{
				$qPos = strpos ( $Query, "?" );
				$Query = substr ( $Query, 0, $qPos - 1 ) . "'{$arg}'" . substr ( $Query, $qPos );
			}
			return $Query;
	}
	/**
	 * Holds the query string for profiling
	 *
	 * @var string
	 */
	private $_queryString;
	/**
	 * Holds query arguments for profiling
	 *
	 * @var array
	 */
	private $_queryParams;
	/**
	 * Holds list of queries run
	 *
	 * @var array
	 */
	private $_queryList = array ();
	private function __querySortByTime($query1,$query2)
	{
		return $query1['averageTime']<$query2['averageTime'];
	}
	/**
	 * Returns a 2D array, having arrays of time, averageTime, count and query.
	 * @return array
	 */
	public function QueryStats()
	{
	
		usort($this->_queryList,array($this,"__querySortByTime"));
		return $this->_queryList;
	}
	private function Profile($time)
	{
		if (jf::$RunMode->IsDevelop())
		{
			$hash = dechex ( crc32 ( $this->_queryString ) );
			$record = array ('averageTime' => $time, 'count' => 1 ,"time" => $time, "query" => $this->_queryString
					//, "params" => array($this->_queryParams)
						
			);
			if (isset ( $this->_queryList [$hash] ))
			{
				$this->_queryList [$hash] ['time'] += $time;
				$this->_queryList [$hash] ['count'] ++;
				$this->_queryList [$hash] ['averageTime']=$this->_queryList [$hash] ['time']*1000/$this->_queryList [$hash] ['count']. " miliseconds";
				// 				$this->_queryList [$hash] ['params'][]=$this->_queryParams;
			}
			else
				$this->_queryList [$hash] = $record;
		}
	}
	/**
	 * wrappers should call this before running any query
	 *
	 * @param $args array
	 *        	of arguments of function
	 */
	public function QueryStart($args)
	{
		$this->_queryString = $args [0];
		array_shift ( $args );
		$this->_queryParams = $args; 
	
		$this->queryProfiler=new Profiler();
	}
	/**
	 * wrappers should call this after running any query
	 * handles debugging and timing of queries
	 */
	public function QueryEnd()
	{
		$time=$this->queryProfiler->Timer();
		$this->Profile($time);
		$this->QueryTime += $time;
		$this->QueryCount ++;
	}
	/**
	 * increase number of queries excuted with this instance
	 * @param unknown_type $val
	 */
	public function increaseQueryCount()
	{
		$this->QueryCount+1;
	}
	/**
	 * returns number of queries executed with this instance
	 *
	 * @return int
	 */
	public function QueryCount()
	{
		return $this->QueryCount;
	}
	/**
	 * returns time for queries consumed by this instance's database
	 *
	 * @return double
	 */
	public function QueryTime()
	{
		return $this->QueryTime;
	}
		
}
/**
 * BaseDatabase class, intended as parent for all database wrappers
 * See comments for conventions
 * All methods starting with capital are jf introduced (for simplicity)
 * all other methods are PDO interface
 * 
 * @author abiusx
 *        
 */
abstract class BaseDatabase extends trait_DatabaseProfiler
{
// 	use DatabaseProfiler;

	/**
	 * 
	 * @var DatabaseSetting
	 */
	public $Config; 
	
	protected $ListOfTable = false;
	
	protected $QueryCount;
	protected $QueryTime;
	private $tempQueryTime; // for QueryTimeIn()
	function __construct(DatabaseSetting $db)
	{
		$this->Config=$db;
	}

	/**
	 * returns insertion ID
	 */
	abstract function LastID();
	function __get($name)
	{
		if ($name == "lastInsertId")
			return $this->LastID;
	}
	
	/**
	 *
	 *
	 * run query, return affected rows
	 * SHOULD do query timing
	 * 
	 * @return int affected rows
	 */
	abstract function exec($Query);
	/**
	 *
	 *
	 * runs a query and returns result statement
	 * SHOULD do query timing
	 * 
	 * @param string $QueryString        	
	 * @return \mysqli_result
	 */
	abstract function query($QueryString);
	
	/**
	 * escapes an string and return.
	 * 
	 * @param string $Param        	
	 */
	abstract function quote($Param);
	/**
	 *
	 *
	 * alias for quote
	 * 
	 * @param unknown_type $Param        	
	 */
	function Escape($Param)
	{
		return $this->quote ( $Param );
	}
	/**
	 *
	 *
	 * prepare a query before executing it and returns result statement
	 * 
	 * @param string $QueryString        	
	 * @return BaseDatabaseStatement
	 */
	protected $cache= array();
	abstract protected function PrepareStatement($Query);
	function prepare($QueryString)
	{
		$Index=crc32($QueryString);
		if(isset($this->cache[$Index]))
			return $this->cache[$Index];
		else
			return $this->cache[$Index]=$this->PrepareStatement($QueryString);
	}
	/**
	 *
	 *
	 * Perform an SQL operation by preparing query and binding params and
	 * running the query,
	 * if it was an INSERT, return last insert ID,
	 * if it was DELETE or UPDATE, return affected rows
	 * and if it was SELECT, return 2D result array
	 * 
	 * @param string $Query        	
	 * @return mixed
	 */
	function SQL($Query)
	{
		$args = func_get_args ();
		array_shift ( $args );
		$statement = $this->prepare ( $Query );
		if (count ( $args ) >= 1)
			call_user_func_array ( array ($statement, "bindAll" ), $args );
		$statement->execute ();
		$type = substr ( trim ( strtoupper ( $Query ) ), 0, 6 );
		if ($type == "INSERT")
		{
			$res = $this->LastID (); // returns 0 if no auto-increment found
			if ($res == 0)
				$res = $statement->rowCount ();
			return $res;
		}
		elseif ($type == "DELETE" or $type == "UPDATE" or $type == "REPLAC")
			return $statement->rowCount ();
		elseif ($type == "SELECT")
		{
			$r=$statement->fetchAll();
			return $r;
		}
		else
			return null;
	}
	static $AutoInitialize = true;
	/**
	 * This function sets up database from install files.
	 * Calls initializeSchema and initializeData.
	 * An adapter should reimplement this and use mutliple query function and
	 * native syntax
	 */
	function Initialize()
	{
		$this->InitializeSchema();
		$this->InitializeData();
	}
	/**
	 * this function sets up database from install files, but only
	 * empties tables and inserts initial data into them.
	 * This is much faster than Initialize for testing fixture.
	 * Only tables with matching prefix are truncated.
	 */
	function InitializeData()
	{
		$this->TruncateTables ();
		
		$Query = $this->GetDataSQL ();
		$Queries = explode ( ";", $Query );
		foreach ( $Queries as $Q )
			$this->query ( $Q );
	}
	
	/**
	 * Initializes the schema by creating tables, only if no tables are found
	 */
	function InitializeSchema()
	{
		if ($this->ListOfTable)
			return ;
		$Query = $this->GetSchemaSQL();
		$Queries = explode ( ";", $Query );
		foreach ( $Queries as $Q )
			$this->query ( $Q );
	}
	/**
	 * Returns list of tables in the database matching table prefix configuration
	 * 
	 * @param boolean $All return all tables, not just matching prefixes        	
	 * @return array null
	 */
	public function ListTables($All=false)
	{
		$TablesQuery = $this->SQL ( "SELECT table_name
				FROM information_schema.tables
				WHERE TABLE_SCHEMA = '{$this->Config->DatabaseName}'" );
		$out = array ();
		if (is_array ( $TablesQuery ))
		{
			foreach ( $TablesQuery as $t )
			{
				$prefix=substr($t ['table_name'], 0, strlen($this->Config->TablePrefix));
				if($All or $prefix==$this->Config->TablePrefix )
					$out [] = $t ['table_name'];
			}
		}
		$this->ListOfTable = true;
		return $out;
	}
	/**
	 * Drops tables of a database that have a matching prefix
	 */
	function DropTables()
	{
		$tables = $this->ListTables ();
		if (is_array ( $tables ))
			foreach ( $tables as $tableName )
				$this->SQL ( "DROP TABLE " . $tableName );
	}
	
	/**
	 * Truncates all data from tables having a matching prefix
	 * 
	 */
	protected function TruncateTables()
	{
		$tables = $this->ListTables ();
		if (is_array ( $tables ))
			foreach ( $tables as $tableName )
				$this->SQL ( "TRUNCATE " . $tableName );
	}
	
	/**
	 * Gets sql from a setup sql file
	 * 
	 * @param string $Type        	
	 * @throws Exception
	 */
	private function GetSQL($Type)
	{
		$Adapter = substr ( get_class ( $this ), strlen ( "jf\\DB_" ) );
		$SetupFile = realpath ( __DIR__ . "/../../../../" . self::$DatabaseSetupFolder . "{$Adapter}.{$Type}.sql" );
		if (file_exists ( $SetupFile ))
		{
			return str_replace ( "PREFIX_", $this->Config->TablePrefix, file_get_contents ( $SetupFile ) );
		}
		else
			throw new \Exception ( "No database setup file available for '{$Adapter}'." );
	}
	/**
	 * Returns the SQL for schema generation
	 * 
	 * @return string
	 */
	protected function GetSchemaSQL()
	{
		return $this->GetSQL ( "schema" );
	}
	/**
	 * Returns the SQL for initial data
	 * 
	 * @return string
	 */
	protected function GetDataSQL()
	{
		return $this->GetSQL ( "data" );
	}
	/**
	 * holds the path to setup sql files
	 * @var string
	 */
	protected static $DatabaseSetupFolder = "install/_db/";
}
abstract class 

BaseDatabaseStatement
{
	/**
	 *
	 * @var BaseDatabase
	 */
	protected $DB;
	/**
	 *
	 *
	 *
	 * fetch single associative result
	 *
	 * @return array or null
	 */
	abstract function fetch();
	
	/**
	 * fetch all results
	 */
	function fetchAll()
	{
		$out = array ();
		while ( $r = $this->fetch () )
			$out [] = $r;			
		return $out;
	}
	
	/**
	 * bind all params
	 */
	abstract function bindAll();
	/**
	 * Alias for bindAll
	 */
	function Bind()
	{
		$args = func_get_args ();
		return call_user_func_array ( array ($this, bindAll ), $args );
	}
	/**
	 * execute a prepared statement, for executing every query you should prepare it before. 
	 * SHOULD do query timing
	 *
	 * @return boolean
	 */
	abstract function execute();
	
	/**
	 *
	 *
	 *
	 * return number of affected rows by UPDATE, INSERT and DELETE. On some
	 * drivers might return number of selected rows.
	 *
	 * @return int
	 */
	abstract function rowCount();
	
	/**
	 * Bind all, execute and return all
	 *
	 * @return array or null
	 */
	function Run()
	{
		$args = func_get_args ();
		$r = call_user_func_array ( array ($this, 'execute' ), $args );
		if ($r)
			return $this->fetchAll ();
		else
			return null;
	}
} 