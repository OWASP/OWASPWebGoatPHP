<?php
class MainTest extends JTestSuite
{
	function __construct()
	{
		$this->add("jf/test/core/main");
		$this->add("jf/test/lib/main");
	}

	function testStress()
	{
// 		print_(jf::db()->QueryStats());
// 		for ($i=0;$i<100;++$i)
// 			$this->assertTrue(jf::$App->Run(""));

	}

}