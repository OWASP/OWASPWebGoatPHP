<?php
/**
 * need to supply me with $result and $profiler
 * first one being test result object of PHPUnit and second an instance of profiler
 */
function DumpResultRows($ResultArray,$BackgroundColor,$Color,$Text,$Odd=false)
{
	if (count($ResultArray)):?>
<tr style='background-color:<?php exho ($BackgroundColor);?>;color:<?php exho ($Color)?>;font-weight:bold;'>
<td colspan='4' >
	<?php exho (count($ResultArray)); 
	echo " {$Text}";
	?>
	
</td>
</tr>
<?php 
		$n=0;
		foreach ($ResultArray as $test)
		{
			echo "<tr>";
			echo "<td width='50' align='center'>\n\t\t";
			echo ++$n;
			echo "\n</td>";
			$t=$test->failedTest();
			echo "<td>\n\t\t";
			echo get_class($t);
			echo " :: ";
			echo $t->getName();
			echo "\n</td>";
			echo "<td>\n\t\t";
			$e=new Exception();
 			echo $test->getExceptionAsString();
			echo "</td>";
			echo "<td>\n\t\t";
			$trace=($test->thrownException()->getTrace());
			if ($Odd)
			{
				$file=$trace[0]['file'];
				$line=$trace[0]['line'];
			}
			else
			{
				if (isset($trace[3]['file']))
				{
					$file=$trace[3]['file'];
					$line=$trace[3]['line'];
				}
				else
					$file=$line=null;
			}
			if ($file!==null)
			{
				$dir=substr($file, 0,strlen(jf::root()));
				$dir=substr($file,0,strpos($file,DIRECTORY_SEPARATOR,strlen($dir)+1));
				$dir=substr($file,0,strpos($file,DIRECTORY_SEPARATOR,strlen($dir)+1));
				$filename=substr($file,strlen($dir)+1);
				echo $dir."/<strong>{$filename}</strong> :{$line}";
			}
			else
				echo "File: Could not determine, probably because of fatal error.";
			
			echo "\n</td>\n";
			echo "</tr>\n";
		}
		?>
	<?php 
	endif;	
}
?>
<h1>Test Results</h1>

<table border='1' cellpadding='5' cellspacing='0' width='100%' >
<?php if (count ($result->passed())):?>
<tr style='background-color:green;color:white;font-weight:bold;'>
<td colspan='4' >
	<?php exho (count($result->passed())); ?> Tests Passed
</td>
</tr>
<?php
endif;
DumpResultRows($result->failures(), "red", "white", "Tests Failed");
DumpResultRows($result->errors(), "#FF7700", "white", "Tests Have Errors");
DumpResultRows($result->notImplemented(), "yellow", "blue", "Tests Not Implemented",true);
DumpResultRows($result->skipped(), "gray", "white", "Tests Skipped",true);
?>

<tr style='background-color:black;color:white;text-align:right;'>
<td colspan='4'>
	<span style='float:left;'>Time: <?php echo $profiler->Timer()?> seconds <span style='color:gray;'>(<?php 
	printf("%.3fs on %d SQL queries",jf::db()->QueryTime(),jf::db()->QueryCount());
	
	?>)</span></span> Total: <?php echo ($result->count());?> Tests in <?php echo count(\jf\TestLauncher::$TestFiles);?> Files
</td>
</tr>

		
		
		
</table>

<?php
if (!isset($_GET['coverage'])) 
{
	echo "<a href='?coverage=1'>View Code Coverage Analysis</a>\n";
	return;
}
/**
 * CODE COVERAGE ANALYSIS
 */

/**
 * Checks if a line of text starts with another text
 * @param string $line
 * @param string $text
 * @return boolean
 */
function startsWith($line,$text)
{
	return substr($line,0,strlen($text))==$text;
}
/**
 * Checks whether a line of PHP code requires code coverage or not
 * Should be called on consecutive lines to detect multi-line comments.
 * @param string $line
 * @return boolean
 */
function needCoverage($line)
{
	static $isComment=false; 
	$line=trim($line);
	if ($line=="") return false;
	if (startsWith($line,"/*")) {
		$isComment=true;
		return false;
	}
	if (substr($line,-2)=="*/") {
		$isComment=false;
		return false;
	}
	if ($isComment) return false;
	if (startsWith($line,"<"."?php")) return false;
	if (startsWith($line,"namespace")) return false;
	if (startsWith($line,"//")) return false;
	if (startsWith($line,"else") and !startsWith($line,"elseif")) return false;
	if (startsWith($line,"class")) return false;
	if (startsWith($line,"function")) return false;
	if (startsWith($line,"public")) return false;
	if (startsWith($line,"protected")) return false;
	if (startsWith($line,"private")) return false;
	if (startsWith($line,"static")) return false;
	if (startsWith($line,"abstract")) return false;
	if (startsWith($line,"final")) return false;
	if ($line=="{" or $line=="}") return false;
	return true;
}
/**
 * returns an array of lines needing coverage on a file
 * @param string $filename
 * @return array
 */
function linesNeedingCoverage($filename)
{
	$outLines=array();
	$content=file_get_contents($filename);
	$lines=explode("\n",$content);
	$number=0;
	foreach ($lines as $line)
	{
		$number++;
		if (needCoverage($line))
			$outLines[]=$number;
	}
	return $outLines;
}
/**
 * Number of file lines
 * @param string $file
 * @return number
 */
function fileLines($file)
{
	$linecount=0;
	$handle = fopen($file, "r");
	while(!feof($handle)){
		$line = fgets($handle, 4096);
		$linecount = $linecount + substr_count($line, PHP_EOL);
	}
	return $linecount;
}

/**
 * $coverage is the variable holding 2D array, keys are filenames and values 
 * an array of lines with coverage as keys, and number of times they are run as values
 */
if ($coverage)
{
	$files=array_keys($coverage);
	//removing phpunit files, and test files. no coverage report for those needed
	foreach ($files as $k=>$f)
	{
		$prefix=jf::root()."/_japp/plugin/phpunit";
		if (substr($f,0,strlen($prefix))==$prefix)
			unset($files[$k]);
		$prefix=jf::root()."/_japp/test/";
		if (substr($f,0,strlen($prefix))==$prefix)
			unset($files[$k]);
	}
	?>
<h2>Code Coverage Report</h2>
<table border='1' width='100%' cellspacing='0' cellpadding='2' style='font-family:"Courier New";border-color:#DDDDDD;'>
<thead>
<tr>
	<th>Module</th>
	<th>File</th>
	<th width='50'>Total Lines</th>
	<th width='50'>Needing Coverage</th>
	<th width='50'>Having Coverage</th>
	<th width='50'>Lines Left</th>
	<th>Code Coverage</th>
</tr>
</thead>
<tbody>
<?php
	foreach ($files as $k=>$file)
	{
		echo "<tr style='text-align:center;'>\n";
		$totalLines=fileLines($file);
		$linesHavingCoverage=array_keys($coverage[$file]);
		$linesNeedingCoverage=linesNeedingCoverage($file);
		$linesNeedingAndHavingCoverage=array_intersect($linesNeedingCoverage,$linesHavingCoverage);
		$linesLeft=count($linesNeedingCoverage)-count($linesNeedingAndHavingCoverage);
		if ($linesLeft==0) $linesLeft="";
		$codeCoveragePercent=sprintf("%.0f",count($linesNeedingAndHavingCoverage)/count($linesNeedingCoverage)*100);
		$filename=substr($file,strlen(jf::root()));
		echo "<td style='padding-left:5px;text-align:left;'><a href='?coverage=1&file=".urlencode($filename)."'>".exho($filename,true)."</a></td>\n";
		$t=explode("/",$filename);
		$t=end($t);
		echo "<td>{$t}</td>\n";
		echo "<td>{$totalLines}</td>\n";
		echo "<td>".count($linesNeedingCoverage)."</td>\n";
		echo "<td>".count($linesNeedingAndHavingCoverage)."</td>\n";
		echo "<td>{$linesLeft}</td>\n";
		echo "<td><strong>{$codeCoveragePercent}%</strong></td>\n";
		echo "</tr>\n";
	}
	?>	
</tbody>
</table>
	<?php	
	#################################
	# file specific coverage report #
	#################################
	if (isset($_GET['file'])) //code coverage report for a single file
	{
		echo "<a href='?coverage=1'>Clear Report</a>";
		echo "<h2>Code Coverage Report for {$_GET['file']}</h2>\n";
		$filename=jf::root().$_GET['file'];
		$fileCoverage=$coverage[$filename];
		
		$linesHavingCoverage=array_keys($fileCoverage);
		$linesNeedingCoverage=linesNeedingCoverage($filename);
		$linesNeedingAndHavingCoverage=array_intersect($linesNeedingCoverage,$linesHavingCoverage);

		echo "<h3>Statistics</h3>\n";
		echo "<p>".count($linesNeedingCoverage)." lines need coverage, out of which ".count($linesNeedingAndHavingCoverage)." lines have coverage.
		In total ".count($linesHavingCoverage)." lines have coverage, whether needing it or not.</p>\n";
		

		echo "<h3>Code Browser</h3>\n";
		$content=file_get_contents($filename);
		$lines=explode("\n",$content);

		?>
		<div class='codeCoverage'>
		<?php
			$number=0; 
			end($fileCoverage);
			$maxLineNumber=key($fileCoverage);
			$width=strlen($maxLineNumber);
			foreach ($lines as $line)
			{
				$lineSafe=exho($line,true);
				$number++;
				$lineNumber=str_replace(" ","&nbsp;",sprintf("%{$width}d",$number));
				$covered=false;
				$need=needCoverage($line);
				$covered=array_key_exists($number,$fileCoverage);
				if ($need)
					if ($covered)
						echo "<pre class='covered'>{$lineNumber}  {$lineSafe}</pre>\n";
					else
						echo "<pre class='uncovered'>{$lineNumber}  {$lineSafe}</pre>\n";
				else
					if ($covered)
						echo "<pre class='noneed'>{$lineNumber}  {$lineSafe}</pre>\n";
					else
						echo "<pre>{$lineNumber}  {$lineSafe}</pre>\n";
					
						
			}
		?>		
		</div>
		<?php
		echo "<strong>Legend:</strong> <span style='background-color:#FFAAAA;color:black;'>Red: No Coverage</span> <span style='background-color:#AAFFAA;color:black;'>Green: Has Coverage</span>
		 <span style='background-color:yellow;color:black;'>Yellow: Has coverage but not needed</span> <span style='background-color:white;color:black;'>White: No need for coverage</span>\n";
		
	}
	
}
?>

<style>
.codeCoverage {
	border:1px solid gray;
	margin:5px;
}
.codeCoverage pre {
	margin:0;
	padding:2px;
}
.codeCoverage .noneed {
	background-color:yellow;
}
.codeCoverage .covered {
	background-color:#AAFFAA;
}
.codeCoverage .uncovered {
	background-color:#FFAAAA;
}
		
</style>