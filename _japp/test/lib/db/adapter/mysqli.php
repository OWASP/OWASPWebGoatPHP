<?php
jf::import("jf/test/lib/db/adapter/base");
class LibDbMysqliTest extends LibDbBaseTest
{
	private static $Default;
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		self::$Default=\jf\DatabaseManager::$DefaultIndex;
		$setting=\jf\DatabaseManager::Configuration();
		$config=new \jf\DatabaseSetting("mysqli", $setting->DatabaseName, $setting->Username, $setting->Password, $setting->Host, $setting->TablePrefix);
		\jf\DatabaseManager::AddConnection($config,2);
		\jf\DatabaseManager::$DefaultIndex=\jf\DatabaseManager::FindIndex($config);
	}
	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
		\jf\DatabaseManager::$DefaultIndex=self::$Default;
	}
	function testQuote()
	{
		$insDb=jf::db();
		$this->assertEquals("\'quote-test\'",$insDb->quote("'quote-test'"));
	}
}

class LibDbStatementMysqliTest extends LibDbStatementBaseTest
{
	private static $Default;
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		self::$Default=\jf\DatabaseManager::$DefaultIndex;
		$setting=\jf\DatabaseManager::Configuration();
		$config=new \jf\DatabaseSetting("mysqli", $setting->DatabaseName, $setting->Username, $setting->Password, $setting->Host, $setting->TablePrefix);
		\jf\DatabaseManager::AddConnection($config,2);
		\jf\DatabaseManager::$DefaultIndex=\jf\DatabaseManager::FindIndex($config);
	}
	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
		\jf\DatabaseManager::$DefaultIndex=self::$Default;
	}
}