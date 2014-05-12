<?php
namespace jf;
/**
 * This is in charge of getting test results as callbacks after each one is run
 * @author abiusx
 *
 */
class TestListener implements \PHPUnit_Framework_TestListener
{
	function Finish()
	{
		$this->Dump("\n");
	}
	function Dump($Data)
	{
		if (defined("STDERR"))
		{
			fwrite(STDERR, $Data);
			fflush(STDERR);
		}
		else
		{
			#TODO: find a way to give feedback to user while he's waiting, without dumping any data so that buffering and session are corrupt! 
		}
		
	}
	public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
	{
		$this->Dump("E");
	}
	
	/**
	 * A failure occurred.
	 *
	 * @param  \PHPUnit_Framework_Test                 $test
	 * @param  \PHPUnit_Framework_AssertionFailedError $e
	 * @param  float                                  $time
	*/
	public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		$this->Dump("F");
		
	}
	
	/**
	 * Incomplete test.
	 *
	 * @param  \PHPUnit_Framework_Test $test
	 * @param  \Exception              $e
	 * @param  float                  $time
	*/
	public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
	{
		$this->Dump("I");
		
	}
	
	/**
	 * Skipped test.
	 *
	 * @param  \PHPUnit_Framework_Test $test
	 * @param  \Exception              $e
	 * @param  float                  $time
	 * @since  Method available since Release 3.0.0
	*/
	public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
	{
		$this->Dump("S");
		
	}
	
	/**
	 * A test suite started.
	 *
	 * @param  \PHPUnit_Framework_TestSuite $suite
	 * @since  Method available since Release 2.2.0
	*/
	public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
	{
		
	}
	
	/**
	 * A test suite ended.
	 *
	 * @param  \PHPUnit_Framework_TestSuite $suite
	 * @since  Method available since Release 2.2.0
	*/
	public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
	{
		
	}
	
	/**
	 * A test started.
	 *
	 * @param  \PHPUnit_Framework_Test $test
	*/
	public function startTest(\PHPUnit_Framework_Test $test)
	{
		
	}
	
	/**
	 * A test ended.
	 *
	 * @param  \PHPUnit_Framework_Test $test
	 * @param  float                  $time
	*/
	public function endTest(\PHPUnit_Framework_Test $test, $time)
	{
		$this->Dump(".");
		
	}
	
}
