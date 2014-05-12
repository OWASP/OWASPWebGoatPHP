<?php
namespace jf;
abstract class Model 
{
	protected function TablePrefix()
	{
		return DatabaseManager::Configuration()->TablePrefix;
	}
	
	protected function ModuleFile($Module=null)
	{
		if ($Module===null)
			$Module=$this->ModuleName();
		return jf::moduleFile($Module);
	}
	/**
	 * returns module name for this object in format control/demo/__catch
	 */
	protected function ModuleName($Object=null)
	{
		if ($Object===null) $Object=$this;
		$reflector = new \ReflectionClass(get_class($Object));
		$filename=($reflector->getFileName());
		$filename_inside_jf=substr($filename,strlen(jf::root())+1);
		
		$Parts=explode(DIRECTORY_SEPARATOR,$filename_inside_jf);
		$Type=array_shift($Parts);
		if ($Type=="_japp")
			array_unshift($Parts, "jf");
		
		return substr(implode("/",$Parts),0,-4); //omit .php
	}
	
	
}

	

?>