<?php
class XuserTest extends JTest
{
	public $x;
	function __construct()
	{
		$this->x=new XuserPlugin();
	}
	
	function testCreateUser()
	{
		$rand=mt_rand(1,1000000);
		$R=$this->x->User_Create("test_user{$rand}","test_pass","test@test_user.com");
		$this->assertNotNull($R);
		$R=$this->x->User_Create("test_user{$rand}","test_pass","test@test_user.com");
		$this->assertNull($R);	
	}	
	function testRemoveUser()
	{
		$rand=mt_rand(1,1000000);
		$R=$this->x->User_Create("test_userz{$rand}","test_pass","test@test_user.com");
		$this->assertTrue($this->x->User_Exists("test_userz{$rand}"));
		$this->x->User_Remove("test_userz{$rand}");
		$this->assertFalse($this->x->User_Exists("test_userz{$rand}"));
		
	}
	function testUserID()
	{
		$rand=mt_rand(1,1000000);
		$R=$this->x->User_Create("test_userz{$rand}","test_pass","test@test_user.com");
		$R2=$this->x->User_ID("test_userz{$rand}");
		$this->assertEqual($R,$R2);
		
	}
	function testUserInfo()
	{
		$rand=mt_rand(1,1000000);
		$R=$this->x->User_Create("test_userz{$rand}","test_pass","test@test_user.com");
		$R2=$this->x->User_Info("test_userz{$rand}");
		$this->assertEqual("test@test_user.com",$R2['Email']);
		$this->assertEqual("test_userz{$rand}",$R2['Username']);
		$this->assertEqual($R,$R2['UserID']);
		
	}
	function testUserExists()
	{
		$R=$this->x->User_Exists("user{$rand}");
		$this->assertFalse($R);
		
		$rand=mt_rand(1,1000000);
		$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		
		$R=$this->x->User_Exists("user{$rand}");
		$this->assertTrue($R);
	}
	function testUserCount()
	{
		$c1=$this->x->User_Count();
		$rand=mt_rand(1,1000000);
		$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		$c2=$this->x->User_Count();
		$this->assertEqual($c1+1,$c2);
	}
	function testClearUsers()
	{
		$rand=mt_rand(1,1000000);
		$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		$c2=$this->x->User_Count();
		$this->x->User_DeleteAll(true);
		$c3=$this->x->User_Count();
		$this->assertEqual($c3,0);
		$this->assertNotEqual($c2,0);
	}
	function testIsLocked()
	{
		$rand=mt_rand(1,1000000);
		$ID=$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		$this->assertFalse($this->x->IsLocked($ID));
		$this->x->Lock($ID);
		$this->assertTrue($this->x->IsLocked($ID));
		$this->x->Unlock($ID);
		$this->assertFalse($this->x->IsLocked($ID));
	}
	function testLogin()
	{
		
		$rand=mt_rand(1,1000000);
		$ID=$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		for ($i=0;$i<$this->x->LockCount;++$i)
		{
			$this->assertFalse($this->x->IsLocked($ID));	
			$this->x->Login($ID,"test_passx");
		}	
		$this->assertTrue($this->x->IsLocked($ID));	
		$this->x->Login($ID,"test_pass");	
		$this->assertFalse($this->x->IsLocked("user{$rand}"));	
		$this->x->Login($ID,"test_pass");	
		$this->assertFalse($this->x->IsLocked("user{$rand}"));	
	}
	function testLogout()
	{
		$rand=mt_rand(1,1000000);
		$ID=$this->x->User_Create("user{$rand}","test_pass","test@test_user.com");
		$this->assertFalse($this->x->IsLoggedIn($ID));
		$this->x->Login("user{$rand}","test_passz");
		$this->assertFalse($this->x->IsLoggedIn($ID));
		$this->x->Login("user{$rand}","test_pass");
		$this->assertTrue($this->x->IsLoggedIn($ID));
		$this->x->Logout($ID);
		$this->assertFalse($this->x->IsLoggedIn($ID));
		$this->x->Logout($ID);
		$this->assertFalse($this->x->IsLoggedIn($ID));
	}
	
}