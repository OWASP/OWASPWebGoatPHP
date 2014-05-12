<?php
/**
 * need to supply me with $result and $profiler
 * first one being test result object of PHPUnit and second an instance of profiler
 */
function DumpResultRows($ResultArray, $Symbol, $Text, $Odd = false)
{
	if (count ( $ResultArray ))
	{
		echo $Symbol.(count ( $ResultArray )) ." ".$Text."\n";
		$n = 0;
		foreach ( $ResultArray as $test )
		{
			echo ++ $n;
			echo ")  ";
			$t = $test->failedTest ();
			
			echo get_class ( $t );
			echo " :: ";
			echo $t->getName ();
			
			echo "\t";
			
			$e = new Exception ();
			echo $test->getExceptionAsString ();
			
			echo "\t";
			
			$trace = ($test->thrownException ()->getTrace ());
			if ($Odd)
				$file = $trace [0] ['file'];
			else
				$file = $trace [3] ['file'];
			$dir = substr ( $file, 0, strlen ( jf::root () ) );
			$dir = substr ( $file, 0, strpos ( $file, DIRECTORY_SEPARATOR, strlen ( $dir ) + 1 ) );
			$dir = substr ( $file, 0, strpos ( $file, DIRECTORY_SEPARATOR, strlen ( $dir ) + 1 ) );
			$filename = substr ( $file, strlen ( $dir ) + 1 );
			echo $filename;
			echo "\n";
		}
		echo str_repeat("-",80)."\n";
	}
}
?>
                    -----======== Test Results ========-----
<?php

if (count ( $result->passed () ))
	echo "✓  ".(count ( $result->passed () )) . " Tests Passed\n".str_repeat("-", "80")."\n";
DumpResultRows ( $result->failures (), "✖  ","Tests Failed" );
DumpResultRows ( $result->errors (), "◉ ", "Tests Have Errors" );
DumpResultRows ( $result->notImplemented (), "✎  ", "Tests Not Implemented", true );
DumpResultRows ( $result->skipped (), "◎  ", "Tests Skipped", true );
echo "\tTotal: " . ($result->count ()) . " Tests in " . count ( \jf\TestLauncher::$TestFiles ) . " Files (".$profiler->Timer()." seconds)\n";
		
