<?php
class LibDatabaseManagerTest extends JDbTest
{
	function testAddConnection()
	{
		$userConfig= \jf\DatabaseManager::Configuration("test");
		$dbConfig= new \jf\DatabaseSetting($userConfig->Adapter, "db_name", $userConfig->Username, $userConfig->Password, $userConfig->Host, $userConfig->TablePrefix);
		\jf\DatabaseManager::AddConnection($dbConfig); 
		$this->assertNotNull(\jf\DatabaseManager::Database(1)->Connection);
		$this->assertSame(\jf\DatabaseManager::Configuration(1),$dbConfig);

		for($i=5; $i<10; $i++)
		{
			$dbConfig= new \jf\DatabaseSetting($userConfig->Adapter, "db_name{$i}", $userConfig->Username, $userConfig->Password, $userConfig->Host, $userConfig->TablePrefix);
			\jf\DatabaseManager::AddConnection($dbConfig,$i);
			$this->assertNotNull(\jf\DatabaseManager::Database($i)->Connection);
			$this->assertSame(\jf\DatabaseManager::Configuration($i),$dbConfig);
		}
	}
	/**
	 * @depends testAddConnection
	 */
	function testRemoveConnection()
	{
		$userConfig= \jf\DatabaseManager::Configuration("test");
		$dbConfig= new \jf\DatabaseSetting($userConfig->Adapter, "db_remove", $userConfig->Username, $userConfig->Password, $userConfig->Host, $userConfig->TablePrefix);
		
		\jf\DatabaseManager::AddConnection($dbConfig,20);
		\jf\DatabaseManager::RemoveConnection($dbConfig);
		try {
			$this->assertNull(\jf\DatabaseManager::Configuration(20));
			$this->fail();
		} catch(Exception $e) {}
		
		\jf\DatabaseManager::AddConnection($dbConfig,"remove");
		\jf\DatabaseManager::RemoveConnection("remove");
		try {
			$this->assertNull(\jf\DatabaseManager::Configuration("remove"));
			$this->fail();
		} catch(Exception $e) {}
	}
	/**
	 * @depends testAddConnection
	 */
	function  testFindIndex()
	{
		$userConfig= \jf\DatabaseManager::Configuration("test");
		$dbConfig= new \jf\DatabaseSetting($userConfig->Adapter, "db_find", $userConfig->Username, $userConfig->Password, $userConfig->Host, $userConfig->TablePrefix);
		\jf\DatabaseManager::AddConnection($dbConfig,"find_index");
	
		$this->assertEquals("find_index", \jf\DatabaseManager::FindIndex($dbConfig));
		$anotherConfig= new \jf\DatabaseSetting($userConfig->Adapter, "db_another_find", $userConfig->Username, $userConfig->Password, $userConfig->Host, $userConfig->TablePrefix);
		$this->assertFalse(\jf\DatabaseManager::FindIndex($anotherConfig));
	}
}