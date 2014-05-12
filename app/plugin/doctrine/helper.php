<?php
class ORM
{
	public static function Repository($Classname)
	{
		if (is_object($Classname))
			$Classname=get_class($Classname);
		return j::ORM()->getRepository($Classname);
	}
	
	/**
	 * 
	 * If queryArguments if a Primary ID, the actual item is returned.
	 * Otherwise you can provide an array of conditions in key=> value format as QueryArguments
	 * You can also provide arguments key1,value1,key2,value2,... for search
	 * @param unknown_type $Classname
	 * @param unknown_type $QueryArguments
	 */
	public static function Find($Classname,$QueryArguments)
	{
		if (is_object($Classname))
		$Classname=get_class($Classname);
		 
		if (!is_array($QueryArguments))
		{
			$args=func_get_args();
			if (count($args)==2)
				return j::ORM()->find($Classname,$QueryArguments);
			
			array_shift($args);
			$n=0;
			$QueryArguments=array();
			foreach ($args as $arg)
			{
				if (++$n%2==0)
				$QueryArguments[$key]=$arg;
				else
				$key=$arg;
			}
		}
		$res = j::ORM()->getRepository("{$Classname}")->findBy($QueryArguments);
		return $res;
	}
	public static function Find1($Classname,$QueryArguments)
	{
		if (is_object($Classname))
		$Classname=get_class($Classname);
	
		if (!is_array($QueryArguments))
		{
			$args=func_get_args();
			if (count($args)==2)
				return j::ORM()->find($Classname,$QueryArguments);
			
			array_shift($args);
			$n=0;
			$QueryArguments=array();
			foreach ($args as $arg)
			{
				if (++$n%2==0)
				$QueryArguments[$key]=$arg;
				else
				$key=$arg;
			}
		}
		$res = j::ORM()->getRepository("{$Classname}")->findOneBy($QueryArguments);
		return $res;
	}
	public static function Query($Classname,$Function=null)
	{
		if (is_object($Classname))
		$Classname=get_class($Classname);
		 
		if ($Function)
		{
			$Arguments=func_get_args();
			array_shift($Arguments);
			array_shift($Arguments);
			$res=call_user_func_array(array(j::ORM()->getRepository($Classname),$Function),$Arguments);
			return $res;
		}
		else
		return j::ORM()->getRepository($Classname);
		 
	}
	public static function Write($Object)
	{
		j::ORM()->persist($Object);
		j::ORM()->flush();
	}
	public static function DQL()
	{
		$args=func_get_args();
		return call_user_func_array(j::DQL, $args);
	}
	static function Persist($Object)
	{
		return j::ORM()->persist($Object);
	} 
	static function Flush()
	{
		return j::ORM()->flush();
	}
	static function Clear()
	{
		return j::ORM()->clear();
	}
	static function Dump($Object)
	{
// 		use Doctrine\Common\Util\Debug;
		Doctrine\Common\Util\Debug::dump($Object);
	}
	static function Delete($Object)
	{
		j::ORM()->remove($Object);
	}
}
