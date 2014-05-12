<?php
class CoreDownloadTest extends JTest 
{
	function testCheckModified()
	{
		$f=new \jf\DownloadManager();
		$_SERVER['http_if_modified_since']="Sat, 06 Apr 2013 10:23:21 GMT";
		$this->assertTrue($f->IsModifiedSince(__FILE__));
	}
	function testFeedData()
	{
	}
}