<?php
namespace jf;
?>
<!-- <?php
		echo WHOAMI;
		?> encountered an error: 
<?php
		echo dirname ( $errfile ).DIRECTORY_SEPARATOR;
		echo basename ( $errfile );
		echo ":";
		echo $errline;
		echo "\n";
		echo ($errstr);
		?> -->
<style>
.ErrorHolder {
	background-color: #FFDD99;
	color: black;
	width: 100%;
	min-height: 50px;
	-moz-border-radius: 3px;
}

.ErrorContent {
	-moz-border-radius: 5px;
	border: 5px outset;
	padding: 5px;
	border: 5px outset;
}

.jFrameworkDetect {
	font-size: 10px;
}

.CodeTable {
	margin-top: 5px;
	font-size: 14px;
	background-color: white;
	padding: 5px;
	border: 3px inset;
}

.StackTrace {
	font-size: 12px;
	font-stretch: wider;
	font-family: verdana;
}

.StackCall {
	color: gray;
}

.StackClass {
	color: green;
}

.StackFunction {
	color: blue;
}

.StackArgs {
	color: red;
}

.StackFilepath {
	font-size: smaller;
	color: gray;
}

.StackFilename {
	color: darkblue;
	font-weight: bolder;
}

.StackLine {
	color: brown;
	font-weight: bold;
	text-decoration: underline;
}

#tooltip {
	width: 80%;
	min-height: 100px;
	display: none;
	border: 5px double;
	position: fixed;
	background-color: white;
	color: black;
	padding: 5px;
}

.ErrorString {
	color: white;
	font-family: verdana;
	font-size: 16px;
	background-color: black;
	padding: 4px 10px 5px 10px;
	margin-bottom: 2px;
	margin-top: 2px;
	border: 3px double;
}
</style>
<div class="ErrorHolder" dir='ltr'>
<div class="ErrorContent"><span class="jFrameworkDetect"><?php
		echo WHOAMI;
	?> has
	detected an error in your application:</span> <br />
<strong><?php if (jf::$ErrorHandler->Shutdown) echo "Fatal ";?>Error <?php
		echo $errno?> </strong> in <span class="StackFilepath"
	style='font-size: medium;'><?php
		echo dirname ( $errfile ) . "/"?><span class='StackFilename'><?php
		echo basename ( $errfile )?></span></span> line <span
	class='StackLine'><?php
		echo $errline;
		?></span> :<br />
<div class='ErrorString'><?php
		echo nl2br ( $errstr );
		?></div>

<table class="CodeTable" width="100%">
	<tr>
		<td style="border-right: 1px solid"><code>
		<?php
		$out = FileLines ( $errfile, $errline );
		$txt = "";
		foreach ( $out as $k => $v )
		{
			if ($k == $errline)
				echo "<strong>$k</strong><br/>" ;
			else
				echo $k . "<br/>";
			if ($v == "")
				$v = " ";
			$txt .= $v;
		}
		?>
		</code></td>
		<td>
	    <?php
		$x = highlight_string ( "<" . "?php" . $txt . "?>", true );
		$l1 = strlen ( '<code><span style="color: #000000">
	<span style="color: #0000BB">&lt;?php' );
		$l2 = strlen ( '<span style="color: #0000BB">?&gt;</span></code>
	' );
		$l = strlen ( $x );
		echo "<code>" . substr ( $x, $l1, $l - $l1 - $l2 ) . "</code>\n";
		
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<hr />
		<h4>Call Stack (most recent last):</h4>
		<div class="StackTrace"><?php

		if (jf::$ErrorHandler->Shutdown)
			echo "Stack trace not available on fatal error.";			
		else
			if ($exception)
			{
				PresentExceptionStack ( $exception );
			}
			else
			{
				$stack = debug_backtrace ();
				PresentErrorStack ( $stack );
			}
		?>
		</div>
		</td>
	</tr>
</table>
</div>
<!-- ErrorContent -->
<div id="tooltip"></div>
<script>
	$("a[content]").bind("mouseover",displayContent);
	$("a[content]").bind("click",function(){$("#tooltip").fadeOut();return false;});
	function displayContent(e)
	{
		//console.dir(e.target);
		$("#tooltip").html($(e.target).attr("content"))
			//.css({"left":(e.target.offsetLeft-$("#tooltip").css("width"))/2,"top":e.target.offsetTop})
			.css({"right":"0","bottom":"0"})
			.fadeIn();
	}
	$("#tooltip").bind("mouseout",hideContent);
	function hideContent(e)
	{
		$("#tooltip").fadeOut();
	}
	</script></div>
<!-- ErrorHolder -->
<?php 
function PresentExceptionStack($Exception)
{
	$StackBacktrace=	$Exception->getTrace ();
	return PresentErrorStack($StackBacktrace,0);
}
function PresentErrorStack($StackBacktrace,$omit=2)
{
	$a = $StackBacktrace;
	$count = count ( $a );
	$a = array_reverse ( $a );
	$depth = 0;
	foreach ( $a as $StackKey => $Stack )
	{
		$depth ++;
		if ($depth > $count - $omit)
			continue; //bypass GeneralError and stack
		$Pre = $depth . str_repeat ( "-", $depth * 2 );
		$v = $Stack;
		{
			if (array_key_exists("class", $v))
				$Class=$v['class'];
			else
				$Class=null;
			if (array_key_exists("type", $v))
				$Type=$v['type'];
			else
				$Type=null;
			$Call = "<span class='StackCall'><span class='StackClass'><b>" . $Class . "</b></span> " . htmlspecialchars ( $Type ) . " <span class='StackFunction'><b>" . $v ["function"] . "</b></span></span>";
			$Call .= "(<span class='StackArgs'>";
			$flag = false;
			foreach ( $v ['args'] as $arg )
			{
					
				if ($flag)
					$Call .= ", ";
				$flag = true;
				if (is_object ( $arg ))
				{
					$x = get_class ( $arg );
					if (method_exists ( $arg, "__toString" ))
						$x = '"' . htmlspecialchars ( $arg->__toString () ) . '"';
					else
					{
						$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>" . htmlspecialchars ( get_class ( $arg ) ) . "</a> ";
					}
				}
				elseif (is_resource ( $arg ))
				{
					$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>[Resource]</a> ";
				}
				elseif (is_array ( $arg ))
				{
					$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>[Array]</a> ";
				}
				else
				{
					$x = '"' . $arg . '"';
				}
				$Call .= $x;
			}
			$Call .= "</span>)";
				
		}
		if (array_key_exists("line", $v))
			$Line = $v ['line'];
		else
			$Line=null;
		if (array_key_exists("file", $v))
			$File = $v ['file'];
		else
			$File=null;
		if (array_key_exists("object", $v))
			$Object = $v ['object'];
		else
			$Object=null;
		$Path = dirname ( $File );
		$File = basename ( $File );
			
		$ErrorPosition = "in <strong>{$File}</strong> (<span class='StackFilepath'><b>" . $Path . "/" . "</b><span class='StackFilename'>{$File}</span></span>) at line <span class='StackLine'>$Line</span> ";
		echo $Pre . $Call ."<br/>";
		echo $Pre . $ErrorPosition ."<br/><br/>";
	}
}
function FileLines($File, $Line)
{
	$Limit = ErrorHandler::$NumberOfLinesToDisplay / 2;
	$f = fopen ( $File, "r" );
	$LineNumber = 0;
			$out = array ();
	while ( ! feof ( $f ) )
	{
		$curline = fgets ( $f );
		$LineNumber ++;
		if ($LineNumber > $Line + $Limit)
			break;
		if ($LineNumber > $Line - $Limit && $LineNumber < $Line + $Limit)
			$out [$LineNumber] = $curline;
	}
	fclose ( $f );
	return $out;
}


?>