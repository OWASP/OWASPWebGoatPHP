<?php
/**
 * Put the following in your FreeTDS.conf
        tds version = 8.0
        encryption = request
        client charset = UTF-8
        text size = 2147483647

Also for FreeTDS to work correctly with smalldatetime fields, add 
mssql.datetimeconvert = Off
to php.ini
 * @author abiusx
 * @version 1.2
 */
namespace jf;
class DB_mssql extends BaseDatabase {
	
	/**
	 * 
	 * Last result handle
	 * @var resource
	 */
	public $Result;
	
	/**
	 * 
	 * Connection handle resource
	 * @var unknown_type
	 */
	public $Connection;
	/**
	 * Debug mode. if set to true DBAL is intended to generate debug output
	 * @var boolean
	 */
	public $Debug = false;
	
	
	/**
	 * 
	 * Last stored procedure
	 * @var DBAL_MSSQL_StoredProcedure
	 */
	public $StoredProcedure;
	
	protected $m_databasename;
	
	public $Charset = "utf8";
	function __construct($Username, $Password, $DatabaseName, $Host = "localhost") {
		if ($Username && $Username != "") {
			$this->Connection = mssql_connect ( $Host, $Username, $Password );
			if (! $this->Connection) {
				trigger_error ( "MSSQL connect error : " . $e );
			}
			mssql_select_db ( $DatabaseName );
		
		// 			if (isset($this->Charset)) $this->DB->set_charset($this->Charset);
		} else {
			$this->Connection = null; //this is mandatory for no-database jFramework        
		}
		$this->m_databasename = $DatabaseName;
	}
	
	function __destruct() {
		if ($this->Connection)
			mssql_close ( $this->Connection );
	}
	
	function LastID() {
		$r = $this->AutoQuery ( "SELECT SCOPE_IDENTITY() AS Result" );
		return $r [0] ['Result'];
	
	}
	
	function quote($Param) {
		return $Param;
	}
	
	function query($QueryString) {
		if (! $this->Connection)
			return null;
		$this->Statement = null;
		$this->QueryCount += 1;
		return mssql_query ( $QueryString, $this->Connection );
	}
	
	function fetch() {
		if ($a = mssql_fetch_assoc ( $this->Result )) {
			return $a;
		} else
			return false;
	}
	
	function fetchAll() {
		if (is_resource($this->Result))
		{
			$out = array ();
			while ( null != ($r = mssql_fetch_assoc ( $this->Result )) )
				$out [] = $r;
			if (count ( $out ) == 0)
				return false;
			return $out;
		}
		else
			return $this->Result;
	}
	
	function exec() {
		if (! $this->Connection)
		throw new Exception("Not implemented."); //TODO
	}
	
	function prepare($Query) {
		throw new Exception( "Not Implemented" );
	}
	function StoredProcedure($Name,$BindParams=null) {
		$this->StoredProcedure = new jfDBAL_MSSQL_StoredProcedure ($this->Connection, $Name );
		if (is_array($BindParams))
		{
			$this->StoredProcedure->BindAll($BindParams);
			return $this->StoredProcedure->Execute();

		}
		else
		{
			return $this->StoredProcedure;
		}
	}
}
class jfDBAL_MSSQL_StoredProcedure {
	/**
	 * 
	 * Connection handle resource
	 * @var unknown_type
	 */
	public $Connection;
	
	/**
	 * 
	 * mssql_statement
	 * @var Resource
	 */
	public $Handle = null;
	
	public $Result;
	
	public static $Types = array ("TEXT" => SQLTEXT, "VARCHAR" => SQLVARCHAR, "NVARCHAR" => SQLVARCHAR, "CHAR" => SQLCHAR, "INT1" => SQLINT1, "INT2" => SQLINT2, "INT4" => SQLINT4, "BIT" => SQLBIT, "FLT4" => SQLFLT4, "FLT8" => SQLFLT8, "FLTN" => SQLFLTN );
	
	function SetName($Name) {
		$this->Handle= mssql_init ( $Name, $this->Connection );
	}
	function __construct($Connection, $Name = null) {
		$this->Connection=$Connection;
		if ($Name)
			$this->SetName ( $Name );
	}
	function BindAll($paramArray)
	{
		foreach ($paramArray as $k=>$v)
		{
			$this->Bind($k,$v);
		}
	}
	
	function Bind($ParameterName, $Variable, $Type = null, $Length = null) {
		if ($Type === null) 
				$Type = "VARCHAR";
		if (is_string($Type))
		{
			$Type = strtoupper ( $Type );
			if (! array_key_exists ( $Type, DBAL_MSSQL_StoredProcedure::$Types ))
				throw new Exception ( "Unknown SQL Server type in list." );
			$Type = DBAL_MSSQL_StoredProcedure::$Types [$Type];
		}
		
		if ($Length)
			mssql_bind ( $this->Handle, $ParameterName, $Variable, $Type, false, $Variable == null, $Length );
		else
			mssql_bind ( $this->Handle, $ParameterName, $Variable, $Type, false, $Variable == null );
	}
	function BindByReference($ParameterName, &$Variable, $Type = null, $Length = null) {
		if ($Type === null)
				$Type = "VARCHAR";
		if (is_string($Type))
		{
			$Type = strtoupper ( $Type );
			if (! array_key_exists ( $Type, DBAL_MSSQL_StoredProcedure::$Types ))
				throw new Exception ( "Unknown SQL Server type in list." );
			$Type = DBAL_MSSQL_StoredProcedure::$Types [$Type];
		}
		if ($Length)
			mssql_bind ( $this->Handle, $ParameterName, $Variable, $Type, false, $Variable == null, $Length );
		else
			mssql_bind ( $this->Handle, $ParameterName, $Variable, $Type, false, $Variable == null );
	}
	function Execute() {
		$this->Result = mssql_execute ( $this->Handle );
		if (is_resource($this->Result))
		{
			
			return $this->GetResults();
		}
		else
			return $this->Result;
	}
	function GetResults() {
		$out = array ();
		do {
			while ( $row = mssql_fetch_assoc ( $this->Result ) ) {
				$out [] = $row;
			}
		} while ( mssql_next_result ( $this->Result ) );
		return $out;
	}
	
	function __destruct() {
		if ($this->Handle)
			mssql_free_statement ( $this->Handle );
	}

}

