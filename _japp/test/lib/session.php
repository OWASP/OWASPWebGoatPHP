<?php
class LibSessionTest extends JDbTest
{
	function setUp()
	{
		parent::setUp();
			
	}
	
	function testRefresh()
	{
		$this->assertFalse(jf::$Session->Refresh());
	}
	
	/**
	 * @depends testRefresh
	 */
	function testDestroy()
	{
		jf::$Session->DestroySession();
		$this->assertTrue(jf::$Session->Refresh());
	}
	/**
	 * @depends testDestroy
	 */
	function testCreate()
	{
		jf::$Session->DestroySession();
		jf::$Session->CreateSession();
		$this->assertFalse(jf::$Session->Refresh());
	}
	
	function testRoll()
	{
		$oldID=jf::$Session->SessionID();
		$this->assertTrue(jf::$Session->RollSession());
		$this->assertNotEquals(jf::$Session->SessionID(), $oldID);
	}
	
	function testSessionID()
	{
		$oldSession=jf::$Session->SessionID();
		$newSession=jf::$Session->SessionID("some-new-id");
		$this->assertEquals($newSession,"some-new-id");
		$this->assertNotEquals($newSession,$oldSession);
	}
	/**
	 * @depends testSessionID
	 */
	function testOnlineVisitors()
	{
		$this->assertGreaterThan(0,jf::$Session->OnlineVisitors());
		for ($i=0;$i<100;++$i)
		{
			jf::$Session->SessionID("session$i");
			jf::$Session->CreateSession();
		}
		$this->assertGreaterThan(100,jf::$Session->OnlineVisitors());
		$this->movetime(\jf\SessionManager::$NoAccessTimeout+10);
		jf::$Session->Refresh();
		jf::$Session->_Sweep(true);
		$this->assertLessThanOrEqual(1,jf::$Session->OnlineVisitors()); //only the active session remains
		
		
	}
	function testDestroySession()
	{
		$_SESSION['something']='something_else';
		$oldSession=jf::$Session->SessionID();
		jf::$Session->DestroySession();
		$this->assertNotEquals(jf::$Session->SessionID(), $oldSession);
		$this->assertEmpty($_SESSION);
	}
	
	function testStressRoll()
	{
		jf::$Session->Refresh();
		for ($i=0;$i<100;++$i)
		{
			$this->assertTrue(jf::$Session->RollSession());
		}
	}
}