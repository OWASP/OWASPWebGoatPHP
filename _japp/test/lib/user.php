<?php
class LibUserTest extends JDbTest
{
	function setUp()
	{
		parent::setUp();
			
	}
	function testCreate()
	{
		$res=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertNotNull($res);
		$this->assertNull(jf::$User->CreateUser("myUsername", "myPassword"));
		$this->assertNull(jf::$User->CreateUser("MYusername", "myPassword"));
	}
	/**
	 * @depends testCreate
	 *
	 */
	function testUsername()
	{
		$userID=jf::$User->CreateUser("myUsername", "myPassword");
		$username=jf::$User->Username($userID);
		$this->assertEquals("myUsername", $username);
	}
	
	/**
	 * @depends testUsername
	 * @depends testCreate
	 */
	function testExists()
	{
		$userID=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertTrue(jf::$User->UserIDExists($userID));
		$this->assertFalse(jf::$User->UserIDExists($userID+5));

		$username=jf::$User->Username($userID);
		$this->assertTrue(jf::$User->UserExists($username));
		$this->assertTrue(jf::$User->UserExists("myUsername"));
		$this->assertFalse(jf::$User->UserExists("nonExistingUser"));
	}
	
	/**
	 * @depends testExists
	 */
	function testEdit()
	{
		$userID=jf::$User->CreateUser("myUsername", "myPassword");
		$username=jf::$User->Username($userID);
		jf::$User->EditUser("myUsername", "newUsername");
		$this->assertFalse(jf::$User->UserExists("myUsername"));
		$this->assertTrue(jf::$User->UserExists("newUsername"));
		
		
		jf::$User->EditUser("newUsername", "newerUsername","newPassword");
		$this->assertFalse(jf::$User->UserExists("newUsername"));
		$this->assertTrue(jf::$User->UserExists("newerUsername"));
		
		$this->assertNull(jf::$User->EditUser("nonExistingUser", "anything"));
		
		jf::$User->CreateUser("myUsername2", "myPassword2");
		$this->assertFalse(jf::$User->EditUser("myUsername2", "newerUsername"));
		
	}
	
	/**
	 * @depends testCreate
	 */
	function testDelete()
	{
		$userid=jf::$User->CreateUser("myUsername", "myPassword");
		$username=jf::$User->Username($userid);
		$this->assertTrue(jf::$User->UserExists("myUsername"));
		jf::$User->DeleteUser("myUsername");
		$this->assertFalse(jf::$User->UserExists("myUsername"));
	}
	
	function testValidate()
	{
		$userid=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertTrue(jf::$User->ValidateUserCredentials("myUsername", "myPassword"));
		$this->assertTrue(jf::$User->ValidateUserCredentials("myusername", "myPassword"));
		$this->assertFalse(jf::$User->ValidateUserCredentials("myUsername_", "mypassword"));
		$this->assertFalse(jf::$User->ValidateUserCredentials("myUsername_", "myPassword"));
		$this->assertFalse(jf::$User->ValidateUserCredentials("myUsername", "myPassword_"));
		
	}
	function testUserID()
	{
		$userid=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertEquals(jf::$User->UserID("myusername"), $userid);
		$this->assertNotEquals(jf::$User->UserID("myusername2"), $userid);
	}	
	/**
	 * @depends testCreate
	 */
	function testLogin()
	{
		$userid=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertFalse(jf::$User->IsLoggedIn($userid));
		
		$this->assertFalse(jf::$User->Login("myUsernamE", "wrong_password"));
		$this->assertFalse(jf::$User->IsLoggedIn($userid));
		
		$this->assertFalse(jf::$User->Login("wrong_username", "myPassword"));
		$this->assertFalse(jf::$User->IsLoggedIn($userid));
		
		$this->assertTrue(jf::$User->Login("myUsernamE", "myPassword"));
		$this->assertTrue(jf::$User->IsLoggedIn($userid));
		
		jf::$User->Logout($userid);
		$this->assertFalse(jf::$User->IsLoggedIn($userid));

		$this->assertTrue(jf::$User->Login("myUsername", "myPassword"));
		//already logged in, default mode is overwrite
		$this->assertTrue($r=jf::$User->Login("myUsername", "myPassword"));
		jf::$User->Login("wrong_username", "myPassword");
		$this->assertTrue(jf::$User->IsLoggedIn($userid));
		$this->assertEquals($userid,jf::CurrentUser());
		jf::$User->Logout();
		$this->assertFalse(jf::$User->IsLoggedIn($userid));
	}
	
	function testExistingLogin()
	{
		$userid=jf::$User->CreateUser("myUsername", "myPassword");
		$this->assertFalse(jf::$User->IsLoggedIn($userid));
		
		\jf\UserManager::$MultipleLoginPolicy=\jf\MultipleLogin::Overwrite;
		$this->assertTrue(jf::$User->Login("myUsernamE", "myPassword"));
		$this->assertTrue(jf::$User->IsLoggedIn($userid));

		$this->assertTrue(jf::$User->Login("myUsername", "myPassword"));
		$this->assertTrue(jf::$User->Login("myUsername", "myPassword"));
		$this->assertTrue(jf::$User->IsLoggedIn($userid));


		jf::$User->LogoutAll($userid);
		\jf\UserManager::$MultipleLoginPolicy=\jf\MultipleLogin::Reject;
		$this->assertTrue(jf::$User->Login("myUsernamE", "myPassword"));
		$this->assertNull(jf::$User->Login("myUsername", "myPassword"));
		$this->assertTrue(jf::$User->IsLoggedIn($userid));
		
		\jf\UserManager::$MultipleLoginPolicy=\jf\MultipleLogin::Allowed;
		$this->assertTrue(jf::$User->Login("myUsernamE", "myPassword"));
		$this->assertTrue(jf::$User->Login("myUsername", "myPassword"));
		$this->assertTrue(jf::$User->IsLoggedIn($userid));
		return;

		for ($i=0;$i<100;++$i)
			$this->assertTrue(jf::$User->Login("myUsername", "myPassword"));
		$this->assertGreater(100,jf::$User->LoginCount($userid));
		jf::$User->LogoutAll($userid);
		$this->assertEquals(0,jf::$User->LoginCount($userid));
		
	}

	function testUserCount()
	{
		$this->assertEquals(jf::$User->UserCount(), 1);
		jf::$User->CreateUser("user2", "123456");
		$this->assertEquals(jf::$User->UserCount(), 2);
		
	}
	

}