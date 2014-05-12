<?php 
if (isset($jfurl))
{
	define("jfembed",$jfurl);
	require_once(__DIR__."/_japp/loader.php");
}
else
	throw new Exception("You should set variable \$jfurl as jframework url before incluing this script.");
?>