<?php
class LibSettingsTest extends JDbTest
{
	function testSaveGeneral()
	{
		$this->assertTrue(jf::SaveGeneralSetting("some_name", "some_value"));
		$this->assertTrue(jf::SaveGeneralSetting("some_name", array("a","b","c")));
	}
	/**
	 * @depends testSaveGeneral
	 */
	function testLoadGeneral()
	{
		jf::SaveGeneralSetting("some_name", "some_value");
		$this->assertEquals(jf::LoadGeneralSetting("some_name"),"some_value");
		jf::SaveGeneralSetting("some_name", array("a","b","c"));
		$this->assertEquals(jf::LoadGeneralSetting("some_name"),array("a","b","c"));
	}
	/**
	 * @depends testLoadGeneral
	 */
	function testSaveGeneralTimeOut()
	{	
		jf::SaveGeneralSetting("some_name", "some_value",jf\Timeout::DAY);
		$this->assertTrue(jf::SaveGeneralSetting("some_name", "some_value",jf\Timeout::DAY));
		$this->movetime(jf\Timeout::DAY);
		jf::$Settings->_Sweep(true);
		$this->assertNull(jf::LoadGeneralSetting("some_name"));
		
		$this->assertTrue(jf::SaveGeneralSetting("some_name", "some_value",1));
		$this->movetime(jf\Timeout::YEAR*10);
		$this->assertNotNull(jf::LoadGeneralSetting("some_name", 1));
			
		$this->movetime(0);
		$this->movetime(jf\Timeout::NEVER-jf::time());
		$this->assertEquals(jf::time(),2147483647);
		jf::$Settings->_Sweep(true);
		$this->assertNull(jf::LoadGeneralSetting("some_name", 1));
	}
	/**
	 * @depends testSaveGeneral
	 */
	function testDeleteGeneral()
	{
		jf::SaveGeneralSetting("some_name", "some_value");
		$this->assertTrue(jf::DeleteGeneralSetting("some_name"));
	}
	function testSaveUser()
	{
		$this->assertTrue(jf::SaveUserSetting("some_name","some_value",1));
		$this->assertTrue(jf::SaveUserSetting("some_name",array("a","b","c"),1));
		try {
			jf::SaveUserSetting("some_name","some_value");
			$this->fail();
		} catch(Exception $e) {}
	}	
	/**
	 * @depends testSaveUser
	 */
	function testLoadUser()
	{
		jf::SaveUserSetting("some_name", "some_value",1);
		$this->assertEquals(jf::LoadUserSetting("some_name",1),"some_value");
		jf::SaveUserSetting("some_name", array("a","b","c"),1);
		$this->assertEquals(jf::LoadUserSetting("some_name",1),array("a","b","c"));
		try {
			jf::LoadUserSetting("some_name");
			$this->fail();
		} catch(Exception $e) {}
	}
	/**
	 * @depends testLoadUser
	 */	
	function testSaveUserTimeOut()
	{
		$this->assertTrue(jf::SaveUserSetting("some_name1", "some_value",1,jf\Timeout::DAY));
		$this->movetime(jf\Timeout::DAY);
		jf::$Settings->_Sweep(true);
		$this->assertNull(jf::LoadUserSetting("some_name1", 1));
		
 		$this->assertTrue(jf::SaveUserSetting("some_name", "some_value",1));
 		$this->movetime(jf\Timeout::YEAR*10);
 		$this->assertNotNull(jf::LoadUserSetting("some_name", 1));
 		
 		$this->movetime(0);
		$this->movetime(jf\Timeout::NEVER-jf::time());
		$this->assertEquals(jf::time(),2147483647);
 		jf::$Settings->_Sweep(true);
 		$this->assertNull(jf::LoadUserSetting("some_name", 1));
	}
	/**
	 * @depends testSaveUser
	 */
	function testDeleteUser()
	{
		jf::SaveUserSetting("some_name", "some_value",1);
		$this->assertTrue(jf::DeleteUserSetting("some_name",1));
		try {
			jf::DeleteUserSetting("some_name");
			$this->fail();
		} catch(Exception $e) {}
	}
	/**
	 * @depends testSaveUser
	 */
	function testDeleteAll()
	{
		for($i=0;$i<5;$i++)
			jf::SaveUserSetting("some_name$i", "some_value$i",1);
		$this->assertEquals(jf::$Settings->DeleteAllUser(1),5);
		for($i=0;$i<5;$i++)
		jf::SaveUserSetting("some_name$i", "some_value$i",1);
		try {
			jf::$Settings->DeleteAllUser();
			$this->fail();
		} catch(Exception $e) {}
	}
	function testSaveSession()
	{
		$this->assertTrue(jf::SaveSessionSetting("some_name", "some_value"));
	}
	/**
	 * @depends testSaveSession
	 */
	function testLoadSession()
	{
		jf::SaveSessionSetting("some_name", "some_value");
		$this->assertEquals(jf::LoadSessionSetting("some_name"),"some_value");
		jf::SaveSessionSetting("some_name", array("a","b","c"));
		$this->assertEquals(jf::LoadSessionSetting("some_name"),array("a","b","c"));
	}
	/**
	 * @depends testLoadSession
	 */
	function testSaveSessionTimeOut()
	{
		$this->assertTrue(jf::SaveSessionSetting("some_name", "some_value",jf\Timeout::DAY));
		$this->movetime(jf\Timeout::DAY+1);
		jf::$Settings->_Sweep(true);
		$this->assertNull(jf::LoadSessionSetting("some_name"));
		
		$this->assertTrue(jf::SaveSessionSetting("some_name2", "some_value",1));
		$this->movetime(jf\Timeout::YEAR*10);
		$this->assertNotNull(jf::LoadSessionSetting("some_name2", 1));
			
		$this->movetime(0);
		$this->movetime(jf\Timeout::NEVER-jf::time());
		$this->assertEquals(jf::time(),2147483647);
		jf::$Settings->_Sweep(true);
		$this->assertNull(jf::LoadSessionSetting("some_name2", 1));
	}
	/**
	 * @depends testSaveSession
	 */
	function testDeleteSession()
	{
		jf::SaveSessionSetting("some_name", "some_value");
		$this->assertTrue(jf::DeleteSessionSetting("some_name"));
	}
}