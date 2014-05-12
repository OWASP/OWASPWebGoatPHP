<?php
namespace jf;
/**
 * Test launcher. Gets a test request and runs an automated test
 * works with PHPUnit
 * @author abiusx
 * @version 1.0
 */
class TestLauncher extends BaseLauncher
{
	
	
	function  __construct($Request)
	{
		$this->Request=$Request;
		if (!$this->Launch())
			jf::run ( "view/_internal/error/404");
	}	
	/**
	 * Launches a test.
	 * @return boolean
	 */
	function Launch()
	{
		//tests are only allowed in development mode
		if (!jf::$RunMode->IsDevelop())
			return false;
		$Parts=explode("/",$this->Request);
		$Type=array_shift($Parts);
		assert($Type=="test");
		if ( $Parts [0] == "sys")
			$prepend="jf";
		else
			$prepend="";
		$Parts[0]="test";
		if ($prepend)
			array_unshift($Parts, $prepend);
		$module=implode("/",$Parts);
		
		return $this->WebLauncher($module);
	}
	
	/**
	 * The TestSuite object responsible of running tests
	 * @var \PHPUnit_Framework_TestSuite
	 */
	public static $TestSuite;
	/**
	 * List of test files run
	 * @var array
	 */
	public static $TestFiles=array();
	/**
	 * Launches a test module for web inspection of results
	 * @param string $module
	 * @return boolean
	 */
	function WebLauncher($module)
	{
		jf::$ErrorHandler->UnsetErrorHandler();
		$this->LoadFramework();

		self::$TestSuite = new \PHPUnit_Framework_TestSuite();
		self::$TestFiles[]=$this->ModuleFile($module);
		self::$TestSuite->addTestFile(self::$TestFiles[0]);
		$result = new \PHPUnit_Framework_TestResult;
		$listener=new TestListener();
		$result->addListener($listener);
		$Profiler=new Profiler();
		if (function_exists("xdebug_start_code_coverage"))
			xdebug_start_code_coverage();
		self::$TestSuite->run($result);
		if (function_exists("xdebug_start_code_coverage"))
			$Coverage=xdebug_get_code_coverage();		
		else
			$Coverage=null;
		$Profiler->Stop();
		$listener->Finish();
		$this->OutputResult($result,$Profiler,$Coverage);
		return true;
	}
	
	/**
	 * Outputs test suite run results in a web friendly interface
	 * @param \PHPUnit_Framework_TestResult $Result
	 * @param Profiler $Profiler
	 */
	function OutputResult($Result,$Profiler,$Coverage=null)
	{
		if (jf::$RunMode->IsCLI())
			$file="cli";
		else
			$file='web';
		jf::run("jf/view/_internal/test/result/{$file}",array("result"=>$Result,"profiler"=>$Profiler,"coverage"=>$Coverage));
		
	}
	
	private function CLILauncher()
	{
		$command="php '".jf::root()."/".self::$PHPUnitPhar."' '".$this->ModuleFile("jf/test/main")."'";
		var_dump($command);
		$res=shell_exec($command);
		var_dump($res);	
		return true;
	}
	
	/**
	 * Loads PHPUnit framework
	 */
	private function LoadFramework()
	{
		\jf\Autoload::AddHandler(function($Classname){ return PhpunitLoaderPlugin::Autoload($Classname);});
		jf::import("jf/model/namespace/public/test");
		jf::import("jf/model/test/listener");
	}
	
}
