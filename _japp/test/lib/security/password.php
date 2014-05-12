<?php
class LibPasswordTest extends JTest
{
	
	function testStrength()
	{
		$this->assertLessThan(\jf\Password::Strength("9122011"),0.1);
		$this->assertLessThan(\jf\Password::Strength("asklwjkbazl"),0.3);
		$this->assertLessThan(\jf\Password::Strength("abkhqp154"),0.5);
		$this->assertLessThan(\jf\Password::Strength("asrpwjk187A2B"),0.8);
		$this->assertLessThan(\jf\Password::Strength("asklwjk187A@5#Z"),0.9);
	}
	function testGenerate()
	{
		$sum=0;
		for($i=0;$i<5;$i++)
		{
			$pass=\jf\Password::Generate(.51);
			$sum+=\jf\Password::Strength($pass);
		}
		$this->assertLessThan($sum/5,.31);
	}
	function testValidate()
	{
		$newPass=new \jf\Password("some_user","some_password");
		$this->assertTrue($newPass->validate("some_user","some_password",$newPass->Password(),$newPass->Salt()));
		
		$newPass=new \jf\Password("some_user","some_password");
		$this->assertFalse($newPass->validate("another_user","some_password",$newPass->Password(),$newPass->Salt()));
		$this->assertFalse($newPass->validate("some_user","another_password",$newPass->Password(),$newPass->Salt()));
		$this->assertFalse($newPass->validate("another_user","another_password",$newPass->Password(),$newPass->Salt()));
	}	
}