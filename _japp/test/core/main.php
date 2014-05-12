<?php
class CoreMainTest extends JTestSuite
{
	function __construct()
	{
		$this->add("jf/test/core/autoload");
		$this->add("jf/test/core/download");
	}
	

}