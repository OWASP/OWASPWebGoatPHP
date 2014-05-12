<?php
class LibXUserTest extends JDbTest
{
	function setUp()
	{
		parent::setUp();
			
	}
	private function create()
	{
		return jf::$XUser->CreateUser("myUsername", "myPassword","some@email.com");
	}
	function testCreate()
	{
		$res=$this->create();
		$this->assertNotNull($res);
		
		$this->assertNull($this->create());
		$this->assertNotNull(jf::$XUser->CreateUser("newUsername","somePassword","new@email.com"));
		$this->assertNull(jf::$XUser->CreateUser("NEWusername","somePassword","new@email.com"));
		$this->assertNull(jf::$XUser->CreateUser("anotherNewUsername","somePassword","new@email.com"));
		$this->assertNull(jf::$XUser->CreateUser("anotherNewUsername","somePassword","NEW@EmAiL.com"));
		
	}
	/**
	 * @depends testCreate
	 *
	 */
	function testUsername()
	{
		$UserID=$this->create();
		$username=jf::$XUser->Username($UserID);
	
		$this->assertEquals("myUsername", $username);
	}
	
	/**
	 * @depends testUsername
	 * @depends testCreate
	 */
	function testExists()
	{
		$UserID=$this->create();
		$this->assertTrue(jf::$XUser->UserIDExists($UserID));
		$this->assertFalse(jf::$XUser->UserIDExists($UserID+5));

		$username=jf::$XUser->Username($UserID);
		$this->assertTrue(jf::$XUser->UserExists($username));
		$this->assertTrue(jf::$XUser->UserExists("myUsername"));
		$this->assertFalse(jf::$XUser->UserExists("nonExistingUser"));
	}
	
	/**
	 * @depends testExists
	 */
	function testEdit()
	{
		$UserID=$this->create();
		$username=jf::$XUser->Username($UserID);
		jf::$XUser->EditUser("myUsername", "newUsername");
		$this->assertFalse(jf::$XUser->UserExists("myUsername"));
		$this->assertTrue(jf::$XUser->UserExists("newUsername"));
		
		
		jf::$XUser->EditUser("newUsername", "newerUsername","newPassword");
		$this->assertFalse(jf::$XUser->UserExists("newUsername"));
		$this->assertTrue(jf::$XUser->UserExists("newerUsername"));
		
		$this->assertNull(jf::$XUser->EditUser("nonExistingUser", "anything"));
		
		$UserID2=jf::$XUser->CreateUser("myUsername2", "myPassword2","another@email.com");
		$this->assertFalse(jf::$XUser->EditUser("myUsername2", "newerUsername"));
		
		$info=jf::$XUser->UserInfo($UserID2);
		$this->assertEquals($info['Email'], "another@email.com");
		jf::$XUser->EditUser("myUsername2", "myUsername2-2","somepassword","new@email.com");
		$info=jf::$XUser->UserInfo($UserID2);
		$this->assertEquals($info['Email'], "new@email.com");
		
	}
	
	/**
	 * @depends testCreate
	 */
	function testDelete()
	{
		$UserID=$this->create();
		$username=jf::$XUser->Username($UserID);
		$this->assertTrue(jf::$XUser->UserExists("myUsername"));
		jf::$XUser->DeleteUser("myUsername");
		$this->assertFalse(jf::$XUser->UserExists("myUsername"));
	}
	
	function testValidate()
	{
		$UserID=$this->create();
		$this->assertTrue(jf::$XUser->ValidateUserCredentials("myUsername", "myPassword"));
		$this->assertTrue(jf::$XUser->ValidateUserCredentials("myusername", "myPassword"));
		$this->assertFalse(jf::$XUser->ValidateUserCredentials("myUsername_", "mypassword"));
		$this->assertFalse(jf::$XUser->ValidateUserCredentials("myUsername_", "myPassword"));
		$this->assertFalse(jf::$XUser->ValidateUserCredentials("myUsername", "myPassword_"));
		
	}
	function testUserID()
	{
		$UserID=$this->create();
		$this->assertEquals(jf::$XUser->UserID("myusername"), $UserID);
		$this->assertNotEquals(jf::$XUser->UserID("myusername2"), $UserID);
	}	
	
	function testActivate()
	{
		$UserID=$this->create();
		$this->assertFalse(jf::$XUser->IsActive($UserID));
		$this->assertTrue(jf::$XUser->Activate($UserID),"can not activate user");
		$this->assertFalse(jf::$XUser->Activate($UserID),"should not be able to activate user twice");
		$this->assertTrue(jf::$XUser->IsActive($UserID));
		$this->assertTrue(jf::$XUser->Deactivate($UserID),"can not deactivate user");
		$this->assertFalse(jf::$XUser->IsActive($UserID));
		
	}
	function testLock()
	{
		$UserID=$this->create();
		$this->assertFalse(jf::$XUser->IsLocked($UserID));
		jf::$XUser->Lock($UserID);
		$this->assertTrue(jf::$XUser->IsLocked($UserID));
		jf::$XUser->Unlock($UserID);
		$this->assertFalse(jf::$XUser->IsLocked($UserID));
		
	}
	
	/**
	 * @depends testActivate
	 */
	function testLogin()
	{
		$UserID=$this->create();
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID));
		
		//Not yet activated
		jf::$XUser->Login("myUsernamE", "myPassword");
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::Inactive);
		
		jf::$XUser->Activate($UserID);
		
		//invalid credentials
		jf::$XUser->Login("myUsernamE", "wrong_password");
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::InvalidCredentials);

		
		//invalid user
		jf::$XUser->Login("wrong_username", "myPassword");
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::NotFound);
		

		//locked
		jf::$XUser->Lock($UserID);
		jf::$XUser->Login("myUsernamE", "myPassword");
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID),"not activated yet");
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::Locked);
		
		//valid login
		$this->assertTrue(jf::$XUser->Unlock($UserID));
		jf::$XUser->Login("myUsernamE", "myPassword");
		$this->assertTrue(jf::$XUser->IsLoggedIn($UserID));
		
		jf::$XUser->Logout($UserID);
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID));

		//try-lock
		for ($i=0;$i<\jf\ExtendedUserManager::$LockCount+1;++$i)
			jf::$XUser->Login("myUsername", "wrong_password");
		jf::$XUser->Login("myUsername", "myPassword");
		$this->assertFalse(jf::$XUser->IsLoggedIn($UserID),"not activated yet");
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::Locked);
		
		//time unlock
		$this->movetime(\jf\ExtendedUserManager::$LockTime+10);
		jf::$XUser->Login("myUsername", "myPassword");
		$this->assertTrue(jf::$XUser->IsLoggedIn($UserID),jf::$XUser->LastError());
		$this->resettime();
		
		//time password expire
		$newID=jf::$XUser->CreateUser("abiusx", "123456","me@abiusx.com");
		jf::$XUser->Activate($newID);
		$this->movetime(\jf\ExtendedUserManager::$PasswordLifeTime+10);
		$this->assertFalse(jf::$XUser->Login("abiusx", "123456"));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::PasswordExpired);
		
	}


	function testExtend()
	{
		$rawUser=jf::$User->CreateUser("rawUser", "rawPassword");
		$this->assertFalse(jf::$XUser->UserIDExists($rawUser));
		$this->assertTrue(jf::$XUser->Extend($rawUser, "rawuser@email.com"));
		$this->assertTrue(jf::$XUser->UserIDExists($rawUser));
	}

	function testResetPassword()
	{
		$UserID=$this->create();
		jf::$XUser->Activate($UserID);
		$tempPass=jf::$XUser->TemporaryResetPassword($UserID);
		$this->assertFalse(jf::$XUser->Login("myusername",$tempPass));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::TemporaryValidPassword);
		
		$this->movetime(\jf\ExtendedUserManager::$TemporaryPasswordTime+5);
		$this->assertFalse(jf::$XUser->Login("myusername",$tempPass));
		$this->assertEquals(jf::$XUser->LastError, \jf\ExtendedUserErrors::InvalidCredentials);
		
	}

	function testFindByEmail()
	{
		$UserID=jf::$XUser->CreateUser("myUsername", "myPassword","my@email.com");
		$this->assertNull(jf::$XUser->FindByEmail("non@existing.com"));
		$this->assertEquals(jf::$XUser->FindByEmail("my@email.com"), $UserID);
		
		
	}
}