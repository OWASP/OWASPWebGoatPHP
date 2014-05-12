<?php
jf::import("jf/test/lib/db/adapter/pdo_mysql");
class LibDbMariadbTest extends LibDbPdoMysqlTest
{
	private static $Default;
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		self::$Default=\jf\DatabaseManager::$DefaultIndex;
		$setting=\jf\DatabaseManager::Configuration();
		$config=new \jf\DatabaseSetting("mariaDB", $setting->DatabaseName, $setting->Username, $setting->Password, $setting->Host, $setting->TablePrefix);
		\jf\DatabaseManager::AddConnection($config,2);
		\jf\DatabaseManager::$DefaultIndex=\jf\DatabaseManager::FindIndex($config);
	}
}

class LibDbStatementMariadbTest extends LibDbStatementPdoMysqlTest
{
	private static $Default;
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
		self::$Default=\jf\DatabaseManager::$DefaultIndex;
		$setting=\jf\DatabaseManager::Configuration();
		$config=new \jf\DatabaseSetting("mariaDB", $setting->DatabaseName, $setting->Username, $setting->Password, $setting->Host, $setting->TablePrefix);
		\jf\DatabaseManager::AddConnection($config,2);
		\jf\DatabaseManager::$DefaultIndex=\jf\DatabaseManager::FindIndex($config);
	}
}