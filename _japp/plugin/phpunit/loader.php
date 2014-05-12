<?php
namespace jf;
class PhpunitLoaderPlugin extends Plugin {
	private static $Archive=null;
	private static function Phar()
	{
		return __DIR__ . '/phpunit.phar';	
	}
	private static function ListModules()
	{
		foreach (new \RecursiveIteratorIterator(self::$Archive) as $file) {
			print_($file->getPathname());
		}
	}
	static function Autoload($Classname) {
		if (self::$Archive===null) self::$Archive=$p = new \Phar(self::Phar(), 0);
		$a=explode("_",$Classname);
		$path=implode("/",$a).".php";

		$Prefix="";
		if (count($a)>1)
		{
			if ($a[1]=="Timer")
				$Prefix="PHP_Timer-1.0.4/";
		}
		$InternalPath="{$Prefix}{$path}";
		$file="phar://".self::Phar()."/$InternalPath";
		if (isset(self::$Archive[$InternalPath]))
		{
			require_once($file);
			return true;
		}
		else 	
			return false;
	}

}

