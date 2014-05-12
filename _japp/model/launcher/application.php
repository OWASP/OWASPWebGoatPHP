<?php
namespace jf;
/**
 * Application launcher. Gets a request (of type application) and runs the corresponding controller.
 * @author abiusx
 * @version 1.01
 */
class ApplicationLauncher extends BaseLauncher
{
	protected $Request=null;
	function  __construct($Request)
	{
		$this->Request=$Request;
		$this->Result=$this->Launch();
	}	
	/**
	 * Return a list of classes found in a module
	 * @param string $module
	 * @return array classname
	 * @version 2.0 updated to reflect namespace in class names
	 */
	function GetClasses($module)
	{
		
		$php_code = file_get_contents ( jf::moduleFile ( $module ) );
		$classes = array ();
		$namespace="";
		$tokens = token_get_all ( $php_code );
		$count = count ( $tokens );
		
		for($i = 0; $i < $count; $i ++)
		{
			if ($tokens[$i][0]===T_NAMESPACE)
			{
				for ($j=$i+1;$j<$count;++$j)
				{
					if ($tokens[$j][0]===T_STRING)
						$namespace.="\\".$tokens[$j][1];
					elseif ($tokens[$j]==='{' or $tokens[$j]===';')
						break;
				}
			}
			if ($tokens[$i][0]===T_CLASS)
			{
				for ($j=$i+1;$j<$count;++$j)
					if ($tokens[$j]==='{')
					{
						$classes[]=$namespace."\\".$tokens[$i+2][1];
					}
			}
		}
		return $classes;
		//is_subclass_of(class, parent_class,/* allow first param to be string */true) 
	}
	
	/**
	 * Finds the controllers class in a controller file.
	 * If there are multiple classes defined in that file, this function lists them, then searches for a derivation of \jf\JControl
	 * @param string $ControllerModule
	 * @return null on failure, string classname on success
	 */
	function ControllerClass($ControllerModule,$BaseClassname="\jf\Controller")
	{
		$Classes=$this->GetClasses ( $ControllerModule );
		foreach ($Classes as $class)
		{
			if (is_subclass_of($class,$BaseClassname))
				return $class;
		}
		return null;
	}
	/**
	 * Starts a controller of application.
	 *
	 * @param String $ControllerModule the module path of the controller, e.g control.main
	 * @return true on successful controller call, false otherwise
	 */
	function StartController($ControllerModule)
	{
		#FIXME: where empty string is found in parts, it should be replaced with main
		//Loading controller file
		try
		{
			jf::import ( $ControllerModule );
			$Classname = $this->ControllerClass($ControllerModule);
		} 
		catch ( ImportException $e ) //not found
		{
			$Classname = "";
			//looking for a catch controller on a parent folder
			$CatchControllerModule = $this->GetIterativeCatchController ( $ControllerModule );
			try {
				$LoadStatus = jf::import ( $CatchControllerModule );
				$Classname = $this->ControllerClass($CatchControllerModule);
				
			}
			catch (ImportException $e) //no catch controller too, go with view
			{
				if (Controller::$AutoPresent)
				{
					$Parts=explode("/",$ControllerModule);
// 					$x=array_shift($Parts);
// 					if ($x=="jf")
// 						array_unshift($Parts,"jf");
					$ViewModule=implode("/",$Parts);
					$control = new AutoController();
					if ($control->Start($ViewModule))
						return true;
				}
			}
	
	
		}
		if (class_exists ( $Classname ))
		{
			$control = new $Classname ();
			return $control->Start ();
		}
		return false;
	}
	/**
	 * searches the directory tree for a catch controller, if no direct controller found
	 * @param string $ControllerModule module name of catch controller
	 */
	Protected function GetIterativeCatchController($ControllerModule)
	{
		if (Controller::$IterativeCatchController)
			$Iteration = 1000;
		else
			$Iteration = 1;
	
		$n = 0;
		$Parts=explode("/",$ControllerModule);
		while ( $n <= $Iteration )
		{
			$Part=array_pop($Parts);
			$template = implode("/",$Parts);
			if ($template == "")
				break;
				
			$template = $template . DIRECTORY_SEPARATOR . Controller::$CatchControllerFile;
			if (file_exists ( jf::moduleFile($template)))
				return $template;
		}
		$template_root = "control" . DIRECTORY_SEPARATOR . Controller::$CatchControllerFile;
		return $template_root;
	}
	
	
	/**
	 * Launches an application controller. Returns what the controller returns.
	 * If it is false, a not found error is displayed.
	 * @return boolean
	 */
	function Launch()
	{
		$Parts = explode ( "/", $this->Request );
		assert ( $Parts [0] == "app"); // or $Parts [0] == "sys" );
		$Parts [0] = "control";
		$RequestedModule = implode ( "/", $Parts );
	
		//load the controller module
		if (!$this->StartController ( $RequestedModule ))
		{
			//not found!
			if (! headers_sent ()) # no output done, this check prevents controllers that don't return true to fail
				jf::run ( "view/_internal/error/404");
			return false;
		}
		return true;
	}
	
	
	
}