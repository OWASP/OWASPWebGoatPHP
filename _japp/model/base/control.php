<?php
namespace jf;
/**
 * Base controller class for jframework
 * @author abiusx
 * @version 4.0
 */
abstract class Controller extends Model
{
	static $AutoPresent=true;
	static $IterativeCatchController=true;
	public static $CatchControllerFile="__catch";
	
	static $DefaultView="default";
	/**
	 * View Module
	 *
	 * @var \jf\View
	 */
	public $View;
	
	/**
	 * 
	 * Holds whether this controller has presented its view or not
	 * @var boolean
	 */
	public $Presented = false;

	/**
	 * 
	 * The derived controller should implement this function to handle incoming requests
	 */
	abstract function Start(); //Starts the controller

	/**
	 * Presents a view
	 * @return boolean success
	 */
	public function Present()
	{
		$this->Presented = true;
		return $this->View->Present ($this->ViewModule());
	}
	
	
	function __construct()
	{
		$this->View = new View ();
		jf::$Security->AccessControl($this);
	}
	/**
	 * this would help the developer to be able to use this inside controller code:
	 * $this->Variable='something';
	 * 
	 * this is the same as
	 * 
	 * $this->View->Variable='something';
	 * 
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	function __set($name, $value)
	{
		$this->View->{$name} = $value;
	}

	function __get($name)
	{
		return $this->View->{$name};
	}

	function __isset($name)
	{
		return isset ( $this->View->{$name} );
	}

	function __unset($name)
	{
		unset ( $this->View->{$name} );
	}



	/**
	 * Redirects the application using headers and client-side http headers.
	 * Should be only called within a controller.
	 *
	 * @param String $NewLocation the new application
	 */
	function Redirect($NewLocation, $RedirectParamsAsWell = false,$HowManyParamsToStrip=0)
	{
		if ($RedirectParamsAsWell)
		{
			$x = explode ( "&", HttpRequest::QueryString () );
			while ($HowManyParamsToStrip--) if (is_array ( $x ) && count ( $x ) > 1) array_shift ( $x );
			$x = implode ( "&", $x );
			if ($x) 
			if (strpos ( $NewLocation, "?" ) === false)
				$x = "?{$x}";
			elseif (strpos($NewLocation,"?")===strlen($NewLocation)-1)
				$x = "{$x}";
			else
				$x="&{$x}";
		}
		else
			$x = "";
		header ( "location: {$NewLocation}{$x}" );
		exit ();
	}

	/**
	 * returns View Module name based on current controller name
	 * this View Module name doesnt include view. and handler. 
	 */
	protected function ViewModule($modulename=null)
	{
		if ($modulename===null) $modulename=$this->ModuleName();
		$Parts=explode("/",$modulename);
		$x=array_shift($Parts);
		if ($x=="jf")
			array_shift($Parts); //get control/ off
		array_unshift($Parts,$this->GetCurrentView());
		array_unshift($Parts,"view");
		if ($x=="jf")
			array_unshift($Parts,"jf");
		return implode("/",$Parts);
	}
	
	/**
	 * returns the current view
	 * overload to change view type
	 */
	function GetCurrentView()
	{
		return self::$DefaultView;
	}

	function __destruct()
	{
// 		if (! $this->Presented && self::$AutoPresent) $this->Present ();
	}
	
	
	

}


/**
 * AutoController automatically presents the corresponding view
 * it is intended for controllers with no code.
 *
 */
class AutoController extends Controller
{

	function __construct()
	{
		$this->View=new View();
	}
	/**
	 * Provide the view module to this and it presents it.
	 * @see \jf\Controller::Start()
	 */
	function Start()
	{
		$arg = func_get_arg ( 0 );
		return $this->View->Present ( $this->ViewModule($arg) );
	}
}
abstract class CatchController extends Controller
{
	function Start()
	{
		#FIXME: this should send relative request instead of the whole request
		return $this->Handle(jf::$Request);	
	}
	/**
	 * Catched requests are delivered here, with their relative paths
	 * @param string $RelativeRequest
	 */
	abstract function Handle($RelativeRequest);
	
	/*function Present()
	{
		$arg=func_get_arg(0); //view module
		#TODO: present the given view
	}*/
}


?>