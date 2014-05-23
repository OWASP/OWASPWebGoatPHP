<?php
class MainTest extends JTestSuite
{
	function __construct()
	{
		$this->add("test/InitTest");
		$this->add("test/lesson/main");
	}
}