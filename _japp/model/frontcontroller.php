<?php
namespace jf;
/**
 * Entry point for a jFramework Application.
 * This has functions for handling main application events and objects for handling framework tasks.
 * @version 3.0
 */
class BaseFrontController
{

	protected function GenerateRequestID()
	{
		$this->RequestID = "";
		for($i = 0; $i < 10; ++ $i)
			$this->RequestID .= mt_rand ( 0, 9 );
		return $this->RequestID;
	}


	function __construct()
	{
		if (!jf::$App)
			jf::$App = $this;
		else
			throw new Exception("FrontController already instantiated.");
		$this->GenerateRequestID();
	}

	/**
	 * Loads necessary files to initialize jframework.
	 */
	protected function LoadCoreModules()
	{
		jf::import ( "jf/config/main" ); #jf pre-config

		jf::import ("jf/model/core/autoload");
		Autoload::Register();

		jf::import ( "jf/config/main" );


		jf::$ErrorHandler = new ErrorHandler ();

		//TODO:
		jf::import ( "jf/model/functions" ); //convenient function helpers

		jf::import ( "jf/model/namespace/public/all" ); //global namespace names

	}
	/**
	 * load application specific configurations
	 */
	protected function LoadApplicationConfiguration()
	{
		//Application specific config
		jf::import ( "config/application" );
	}

	/**
	 * Loads necessary jframework libraries, which provide most of the functionality of framework
	 *
	 */
	Protected function LoadLibraries()
	{
		jf::$Profiler=new Profiler();

		jf::$Log = new LogManager ();

		jf::$User = new UserManager ();

		jf::$XUser = new ExtendedUserManager ();

		jf::$Session = new SessionManager ();

		jf::$Settings = new SettingManager ();

		jf::$Security = new SecurityManager ();

		jf::$RBAC = new RBACManager ();

		jf::$Services = new ServiceManager ();

	}


	static $IndexPage="main"; //default page
	/**
	 * Initializes the framework
	 * @param string $Request used to understand the environment. You can re-apply this value to run, but this here should be your URL.
	 *
	 */
	public function Init($Request)
	{
		jf::$BaseRequest=$Request;

		$this->LoadCoreModules (); //core modules

		if (defined("jfembed"))
			jf::$RunMode->Add(\RunModes::Embed);

		$this->LoadApplicationConfiguration ();

		$this->LoadLibraries ();

		$this->Started = true;

		if (!jf::$RunMode->IsEmbed())
			return $this->Run ();

	}

	/**
	 * After loading jFramework and libraries, use this function to run a request type
	 * @param $Request the url request that is supposed to get run
	 */
	public function Run($Request = null)
	{
		if (! $this->Started)
		{
			throw new Exception( "You should init jframework before trying to run a request" );
			return false;
		}
		if ($Request === null)
			$Request = jf::$BaseRequest;
		//fixing request by adding index page
		$Parts=explode("/",$Request);
		if ($Parts[count($Parts)-1]=="")
				$Parts[count($Parts)-1]=self::$IndexPage;
		$Request=implode("/",$Parts);

		jf::run("config/hook/pre"); //pre hook
		$r=$this->_Run($Request);
		jf::run("config/hook/post"); //post hook
		return $r->Result();
	}

	private function _Run($Request)
	{
		$Parts=explode("/",$Request);
		$Type=array_shift($Parts);
		$isFile = array_key_exists ( $Type, FileLauncher::$StaticContentPrefix );
		if ($Type == "app")
			return new ApplicationLauncher( $Request );
		elseif ($isFile)
		return new FileLauncher($Request);
		elseif ($Type == "service")
		return new ServiceLauncher($Request);
		elseif ($Type == "sys")
		return new SystemLauncher($Request);
		elseif ($Type == "test")
		return new TestLauncher( $Request );
		else //if jFramework receives an unknown type of request, it assumes application type
		{
			$Request = "app/" . $Request;
			return new ApplicationLauncher ($Request);
		}
	}

}
class FrontController extends BaseFrontController
{


	static function GetSingleton()
	{
		if (FrontController::$singleton === null)
			FrontController::$singleton = new FrontController ();
		return FrontController::$singleton;
	}
	private static $singleton = null;
}
