<?php
namespace jf;
/**
 * Application launcher. Gets a request (of type application) and runs the corresponding controller.
 * @author abiusx
 * @version 1.0
 */
class SystemLauncher extends ApplicationLauncher
{
	/**
	 * Launches a system (admin interface) controller. Returns what the controller returns.
	 * If it is false, a not found error is displayed.
	 * @return boolean
	 */
	function Launch()
	{
		$Parts = explode ( "/", $this->Request );
		assert ( $Parts [0] == "sys"); // or $Parts [0] == "app" );
		$Parts [0] = "control";
		array_unshift($Parts, "jf"); //go system mode for import
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