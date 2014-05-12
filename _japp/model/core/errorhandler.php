<?php
namespace jf;

/**
 * jFramework Error Handler
 * Handles errors and exceptions and displays them if enabled, and logs instead.
 * @version 2.3
 * @author abiusx
 */
class ErrorHandler
{
	protected $BackupErrorReporting = null;
	function __construct()
	{
		$this->SetErrorHandler ();
	}
	/**
	 * Converts an internal PHP error to an ErrorException object and throws it.
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @throws \ErrorException
	 */
	function Error2Exception($errno, $errstr, $errfile, $errline)
	{
		throw new \ErrorException ( $errstr, $errno, 0, $errfile, $errline );
	}
	/**
	 * Whether to enable jframework ErrorHandler or not
	 * @var unknown_type
	 */
	static $Enabled=true;
	private static $OldState=false;
	/**
	 * This serves as the general catcher of exceptions
	 * @param Exception $e
	 */
	function HandleException(\Exception $e)
	{
		if (!self::$Enabled)
			throw $e;
		$this->PresentError ( $e->getCode (), $e->getMessage (), $e->getFile (), $e->getLine (), $e);
	}
	/**
	 * Sets the appropriate error handler based on configurations
	 */
	function SetErrorHandler()
	{
		if (self::$Enabled && self::$OldState!=self::$Enabled)
		{
			$this->BackupErrorReporting = (error_reporting ());
			error_reporting ( 0 );
			set_error_handler ( array ($this, 'Error2Exception' ));#, E_ALL | E_NOTICE );
			register_shutdown_function ( array ($this, 'ShutdownFunction' ) );
			
		}
		elseif (!self::$Enabled && self::$OldState!=self::$Enabled)
		{
			if ($this->BackupErrorReporting !== null)
				error_reporting ( $this->BackupErrorReporting );
			restore_error_handler ();
			$this->BackupErrorReporting = null;
		}
		self::$OldState=self::$Enabled;
	}
	/**
	 * Unsets jframework error handler and disables it
	 */
	function UnsetErrorHandler()
	{
// 		$previous=self::$Enabled;
		self::$Enabled=false;
		$this->SetErrorHandler();
// 		self::$Enabled=$previous;
	}
	
	/**
	 * The number of lines to display around the line of error
	 * @var integer
	 */
	static public $NumberOfLinesToDisplay = 10;
	
	/**
	 * Whether to output errors to screen or not
	 * @var boolean
	 */
	static $PresentErrors=true;
	
	/**
	 * Outputs a nicely formatted error for web display
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @param \Exception $exception
	 * @return boolean
	 */
	function PresentError($errno, $errstr, $errfile, $errline, $exception = null)
	{
		if (!self::$PresentErrors)
			return false;
		jf::run("jf/view/_internal/error",array(
		"errno"=>$errno,
		"errstr"=>$errstr,
		"errfile"=>$errfile,
		"errline"=>$errline,
		"exception"=>$exception,
		
		));

		/* Don't execute PHP internal error handler */
		return false;
	
	}

	/**
	 * Whether or not a fatal error has occured and the system is shuting down
	 * @var boolean
	 */
	public $Shutdown=false;
	
	/**
	 * This is registered as a PHP shutdown function. It checks if a fatal error is occured, and if so
	 * logs and outputs it.
	 */
	function ShutdownFunction()
	{
		//check to see if shutdown function runned because of error or naturally
		$isError = false;
		$error = error_get_last ();
		if ($error)
		{
			switch ($error ['type'])
			{
				case E_ERROR :
				case E_CORE_ERROR :
				case E_PARSE :
				case E_COMPILE_ERROR :
				case E_USER_ERROR :
// 				case E_NOTICE:
					// 				default:
					$isError = true;
					break;
			}
		}
		if ($isError)
		{
			$this->Shutdown=true;
			$this->PresentError ( $error ['type'], $error ['message'], $error ['file'], $error ['line'] );
		echo "<h1>Fatal Error</h1>
<p>Some fatal error caused the application to quit unexpectedly. The error details have been successfully 
logged for the system administrator to review them later.
We're sorry for the inconvenience.</p>\n";
		if (j::$Log)
			j::Log ( "ShutdownError", "Error type " . $error ['type'] . " : " . $error ['message'] . " (" . $error ['file'] . " at " . $error ['line'] . ")", 5 );
		exit ( 1 );
		}
	}
	
}


?>