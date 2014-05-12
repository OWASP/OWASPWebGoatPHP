<?php
/* jFramework
 * Database Access Layer Library
 */
namespace jf;


class DatabaseSetting extends Model
{
	public $Adapter,$DatabaseName,$Username,$Password,$Host,$TablePrefix;
	function __construct($Adapter,$DatabaseName,$Username,$Password,$Host="localhost",$TablePrefix="jf_")
	{
		$this->Adapter=$Adapter;
		$this->Username=$Username;
		$this->Password=$Password;
		$this->DatabaseName=$DatabaseName;
		$this->Host=$Host;
		$this->TablePrefix=$TablePrefix;
		if($this->TablePrefix=="")
			throw new \Exception("TablePrefix shouldn't be an empty string: ".$this->Adapter);
	}
}
class NoDatabaseSetting extends DatabaseSetting
{
	function __construct()
	{
		$this->Adapter=$this->DatabaseName=$this->Username=$this->Password=$this->Host=$this->TablePrefix=null;
	}
}

jf::import("jf/model/lib/db/base",".");
jf::import("jf/model/lib/db/nestedset/base",".");
jf::import("jf/model/lib/db/nestedset/full",".");

class DatabaseManager extends Model
{
	/**
	 *
	 * @var BaseDatabase
	 */
	protected static $Connections=array();

	/**
	 * Adds a new connection to database manager.
	 * If index is set, the connection is added with the index (which could be a string)
	 * @param DatabaseSetting $dbConfig
	 * @param integer|string $Index
	 * @throws ImportException
	 * @return unknown
	 */
	static function AddConnection(DatabaseSetting $dbConfig,$Index=null)
	{
		$configIndex=self::FindIndex($dbConfig);
		if($configIndex != -1)
			return self::$Connections[$configIndex];
		$Classname="\\jf\\DB_{$dbConfig->Adapter}";
		try {
			jf::import("jf/model/lib/db/adapter/{$dbConfig->Adapter}");
		}
		catch (ImportException $e)
		{
			echo "Database adapter '{$dbConfig->Adapter}' not found.";
			throw $e;
		}
		if ($Index===null)
		{
			return self::$Connections[]=new $Classname($dbConfig);
		}
		else
		{
			return self::$Connections[$Index]=new $Classname($dbConfig);
		}

	}
	/**
	 * Removes a connection from database manager.
	 * @param $arg, It could be instanceof DatabaseSetting or an Index of Connections
	 */
	static function RemoveConnection($arg=null)
	{
		$type=gettype($arg);
		if($arg instanceof DatabaseSetting)
			self::RemoveConfig($arg);
		else if($type=="integer" or $type=="string" or $type="NULL")
			self::RemoveIndex($arg);
	}
	private static function RemoveIndex($Index)
	{
		if($Index===null)
			$Index=self::DefaultIndex;
		$i=0;
		foreach(self::$Connections as $key=>$value)
		{
			if($key == $Index)
			{
				array_splice(self::$Connections, $i, 1);
				break;
			}
			$i++;
		}
	}
	private static function RemoveConfig(DatabaseSetting $dbConfig)
	{
		$i=0;
		foreach(self::$Connections as $Con)
		{
			if($Con->Config == $dbConfig)
			{
				array_splice(self::$Connections, $i, 1);
				break;
			}
			$i++;
		}
	}
	/**
	* Return index of dbConfig in Connections
	* @param DatabaseSetting $dbConfig
	* @return int|string index, if dbConfig not found returns -1
	*/
	static function FindIndex(DatabaseSetting $dbConfig)
	{
		foreach(self::$Connections as $key=>$value)
			if($value->Config == $dbConfig)
				return $key;
		return -1;
	}
	/**
	 * Holds the index of default database, used by db and SQL functions of jf:: accessor
	 * This effectively makes all alias database functions to use the set index.
	 * @var integer
	 */
	static $DefaultIndex=0;
	/**
	 * Returns a database connection
	 * @param integer $Index optional leave to return default
	 * @return BaseDatabase
	 */
	static function Database($Index=null)
	{
		if ($Index===null)
			return self::$Connections[self::$DefaultIndex];
		else
			return self::$Connections[$Index];
	}
	/**
	 * Returns a database connection setting
	 * @param integer $Index optional leave for default
	 * @return DatabaseSetting
	 */
	static function Configuration($Index=null)
	{
		if ($Index===null)
			return self::Database(self::$DefaultIndex)->Config;
		else
			return self::Database($Index)->Config;
	}
}







?>