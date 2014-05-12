<?php
namespace jf;
abstract class Test extends \PHPUnit_Framework_TestCase
{
	function __construct() {
		parent::__construct();
	}
	/**
	 * Adds another module to the test suite
	 * @param string $TestModule
	 */
	function add($TestModule)
	{
		$file=jf::moduleFile($TestModule);;
		if (!in_array($file, TestLauncher::$TestFiles))
		{
			TestLauncher::$TestFiles[]=$file;
			TestLauncher::$TestSuite->addTestFile($file);
		}
	}

	/**
	 * Moves current time to test timing features
	 * @param integer $difference
	 */
	function movetime($difference)
	{
		jf::_movetime($difference);
	}
	/**
	 * reset time to actual time
	 */
	function resettime()
	{
		jf::_movetime(null);
	}
}

abstract class TestSuite extends \PHPUnit_Framework_TestCase
{
	/**
	 * Adds another module to the test suite
	 * @param string $TestModule
	 */
	function add($TestModule)
	{
		$file=jf::moduleFile($TestModule);;
		if (!in_array($file, TestLauncher::$TestFiles))
		{
			TestLauncher::$TestFiles[]=$file;
			TestLauncher::$TestSuite->addTestFile($file);
			
		}
	}
	function testTrue()
	{
		
	}
	
}

abstract class DbTest extends Test
{
	private static $config=null;
	/**
	 * You can override this to provide custom database connection setting
	 * @returns \jf\DatabaseSetting
	 */
	function dbConfig()
	{
		if (self::$config===null)
		{
			$dbConfig=DatabaseManager::Configuration();
			$newConfig= new \jf\DatabaseSetting($dbConfig->Adapter, $dbConfig->DatabaseName, $dbConfig->Username, $dbConfig->Password, $dbConfig->Host, $dbConfig->TablePrefix."_test_");
			self::$config=$newConfig;
		}
		return self::$config;
	}
	function setUp()
	{
		$this->resettime();
		jf::db()->Initialize();
		jf::$Session->Refresh();
	}
	
	private static $initiated=false;
	
	private static $count=0;
	function __construct() {
		parent::__construct();
		self::$count++;
		if (!self::$initiated)
		{
			DatabaseManager::AddConnection($this->dbConfig(),"test");
			DatabaseManager::$DefaultIndex="test";
			self::$initiated=true;
		}
	}
	function __destruct()
	{
		self::$count--;
		if (self::$count==0)
		{
			//FIXME: it doesnt work, probably because of the shutdown nature of system.
// 			jf::db()->DropTables();
			DatabaseManager::$DefaultIndex=0;
		}
	}
		
}
?>