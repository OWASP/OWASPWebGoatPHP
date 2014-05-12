<?php
	function autoload_callback($Classname)
	{
		if ($Classname=="anotherClassThatDoesNotExist")
			return true;
		return false;
	}
class CoreAutoloadTest extends JTest
{
	
	function tearDown()
	{
		\jf\Autoload::ResetRules();
	}
	function testConstruct()
	{
		try {
			$temp=new \jf\Autoload();
			$this->fail();
		}
		catch (\jf\AutoloadIsStaticException $e)
		{
			return;
		}
		
	}
	function testRegister()
	{
		\jf\Autoload::Register();
		
	}
	function testAutoloadExists()
	{
		$this->assertTrue(\jf\Autoload::Autoload("jf\\Model"));
		$this->assertTrue(\jf\Autoload::Autoload("\\jf\\Model"));
		$this->assertFalse(\jf\Autoload::Autoload("Model__"));
	}
	
	function testCoreAutoload()
	{
		$this->assertTrue(\jf\Autoload::Autoload("jf\\MainController"));
		$this->assertTrue(\jf\Autoload::Autoload("\\jf\\MainController"));
		$this->assertFalse(\jf\Autoload::Autoload("jf\\MainController__"));
		$this->assertFalse(\jf\Autoload::Autoload("\\jf\\mainController__"));
	}
	function testAddHandler()
	{
		
		$this->assertFalse(\jf\Autoload::Autoload("classThatDoesNotExist"));
		\jf\Autoload::AddHandler(	function($Classname) {
			if ($Classname=="classThatDoesNotExist")
				return true;
			return false;
		});
		$this->assertTrue(\jf\Autoload::Autoload("classThatDoesNotExist"));

		$this->assertFalse(\jf\Autoload::Autoload("anotherClassThatDoesNotExist"));
		\jf\Autoload::AddHandler("autoload_callback");
		$this->assertTrue(\jf\Autoload::Autoload("anotherClassThatDoesNotExist"));
		
	}
	
	/**
	 * @depends testAddHandler
	 */
	function testRemoveHandler()
	{
		$this->assertTrue(\jf\Autoload::Autoload("classThatDoesNotExist"));
		$r=\jf\Autoload::RemoveHandler(1);
		$this->assertTrue($r);
		$this->assertFalse(\jf\Autoload::Autoload("classThatDoesNotExist"));

		$this->assertTrue(\jf\Autoload::Autoload("anotherClassThatDoesNotExist"));
		$r=\jf\Autoload::RemoveHandler("autoload_callback");
		$this->assertTrue($r);
		$this->assertFalse(\jf\Autoload::Autoload("anotherClassThatDoesNotExist"));
		
				
	}	
	
	function testAddRule()
	{
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass"));
		\jf\Autoload::AddRule("nonExistingClass",__FILE__);
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingClass"));
	}
	/**
	 * @depends testAddRule
	 */
	function testRemoveRule()
	{
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass"));
		\jf\Autoload::AddRule("nonExistingClass",__FILE__);
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingClass"));
		\jf\Autoload::RemoveRule("nonExistingClass");
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass"));
	}
	
	function testAddModule()
	{
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingModel"));
		\jf\Autoload::AddModule("nonExistingModel","jf/model/core/autoload");
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingModel"));
		try {
			\jf\Autoload::AddModule("nonExistingModel","jf/model/file/not/exists");
			$this->fail();
		}
		catch (\jf\AutoloadRuleException $e)
		{
			return;
		}
	}
	
	function testAddRuleArray()
	{
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass1"));
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass2"));
		\jf\Autoload::AddRuleArray(array("nonExistingClass1"=>__FILE__,"nonExistingClass2"=>__FILE__,"nonExistingClass3"=>__FILE__));
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingClass2"));
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass4"));
	}
	
	/**
	 * @depends testAddRuleArray
	 */
	function testRemoveRuleArrayValues()
	{
		\jf\Autoload::AddRuleArray(array("nonExistingClass1"=>__FILE__,"nonExistingClass2"=>__FILE__,"nonExistingClass3"=>__FILE__));
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingClass2"));
		\jf\Autoload::RemoveRuleArrayValues(array("nonExistingClass2","nonExistingClass4"));
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass2"));
		
	}
	/**
	 * @depends testAddRuleArray
	 */
	function testRemoveRuleArrayKeys()
	{
		\jf\Autoload::AddRuleArray(array("nonExistingClass1"=>__FILE__,"nonExistingClass2"=>__FILE__,"nonExistingClass3"=>__FILE__));
		$this->assertTrue(\jf\Autoload::Autoload("nonExistingClass2"));
		\jf\Autoload::RemoveRuleArrayKeys(array("nonExistingClass2"=>null,"nonExistingClass4"=>null));
		$this->assertFalse(\jf\Autoload::Autoload("nonExistingClass2"));
		
	}
	
}