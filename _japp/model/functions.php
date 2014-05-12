<?php

/**
 * 
 * Outputs a translated version of an string
 * @param string $Phrase
 * @param optional $Lang string language
 * @param optional $Target desired language
 * @version 1.08
 */
function tr($Phrase,$Lang=null,$Target=null)
{
	echo trr($Phrase,$Lang,$Target);
}
/**
 * 
 * Returns a translated version of an string
 * @param string $Phrase
 * @param optional $Lang string language
 * @param optional $Target desired language
 * @version 1.08
 */
function trr($Phrase,$Lang=null,$Target=null)
{
	return jf::$i18n->Translate($Phrase,$Lang,$Target);
}

function print_($var)
{
	if ($var===null)
		$data="NULL";
	elseif ($var===false)
		$data="False";
	elseif ($var===true)
		$data="True";
	else
		$data=print_r($var,true);
	if (jf::$RunMode->IsCLI())
		echo $data."\n";
	else
		echo nl2br(str_replace(" ","&nbsp;",htmlspecialchars($data)))."<br/>";
	flush();
	if (ob_get_contents()) ob_flush();
}

function exho($data,$return=false)
{
	if (defined("ENT_HTML401"))
		$t=htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,"UTF-8");
	else
		$t=htmlspecialchars($data,ENT_QUOTES,"UTF-8");
	if ($return)
		return $t;
	else
		echo $t;
		
}

?>
