<?php
namespace jf;
class SecurityManager extends Model
{
	private static $randomSeed=null;
	
	/**
	 * Provides a random 32 bit number
	 * if openssl is available, it is cryptographically secure. Otherwise all available entropy is gathered.
	 * @return number
	 */
	function Random()
	{
		if (function_exists("openssl_random_pseudo_bytes"))
			$random32bit=(int)(hexdec(bin2hex(openssl_random_pseudo_bytes(4))));
		else
		{
			$random64bit="";
			if (self::$randomSeed===null)
			{
				$entropy=1;
				if (function_exists("posix_getpid"))
					$entropy*=posix_getpid();
				if (function_exists("memory_get_usage"))
					$entropy*=memory_get_usage();
				list ($usec, $sec)=explode(" ",microtime());
				$usec*=1000000;
				$entropy*=$usec;
				self::$randomSeed=$entropy;
				mt_srand(self::$randomSeed);
			}
			$random32bit=mt_rand();
		}
		return $random32bit;
	}
	
	
	static $AccessControlFile="__rbac";
	protected function LoadRbac($RbacModule)
	{
		try {
			jf::run($RbacModule);
		}
		catch (ImportException $e)
		{
			return false;
		}
		return true;
		
	}
	public function AccessControl(Controller $ControllerObject)
	{
		$modulename=$this->ModuleName($ControllerObject);
		$Parts=explode("/",$modulename);
		$n = 0;
		while ( $Parts  )
		{
			$Part=array_pop($Parts);
			$rbac_meta=implode("/",$Parts);
			if (count($Parts)==0) break; //non 
			$rbac_meta .= "/". self::$AccessControlFile;
			if ($this->LoadRbac ( $rbac_meta )) return true;
			if ($rbac_meta == "control") break;
		}
		return false;
		
	}
	
	
	function RandomToken($Length=64)
	{
		return substr(hash("sha512",jf::rand()),0,$Length);
	}
}
?>