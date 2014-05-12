<style>
* {
font-size:small;
}
</style>
<table border='1' width='100%' cellpadding='2' cellspacing='0' >
<tr>
<td colspan='4'>
<?php
$Op=j::SQL("SELECT * FROM jf_options");
if ($Op)
foreach ($Op as &$o)
	$o['Value']=unserialize($o['Value']);
echo nl2br(str_replace(" ","&nbsp;",htmlspecialchars(print_r($Op,true))));
?>

</td>
</tr>
</table>