<?php 
if (isset($_GET['action']) && $_GET['action']=="updateSchema")
{
	echo nl2br(print_r(j::$ORM->UpdateSchema(),true));
}
?>
<style>
* {
font-size:small;
}
#title {
	font-size:16px;
}
#title b {
	font-size:20px;
}
</style>
<table border='0' width='100%' cellpadding='2' cellspacing='0' >
<tr>
<td colspan='4' align='center'><span id='title'><?php echo "<b>".trr(reg("app/title"))."</b> (".reg("app/name")." ".reg("app/version").")"?></span></td>
</tr>
<tr>
<td colspan='4'><h2><?php tr("Numerical Status");?></h2></td>
</tr>
<tr align='center'>
<td><?php tr("Online Visitors");?> : <?php echo j::$Session->OnlineVisitors();?></td>
<td title='<?php tr("Pages viewed in current sessions");?>'><?php tr("Active Page Views");?>: <?php $x=j::SQL("SELECT SUM(".reg("jf/session/table/AccessCount").") AS Count FROM ".reg("jf/session/table/name")); echo $x[0]['Count'];?></td>
<td><?php tr("Number of Users");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".jf_Users_Table_Name); echo $x[0]['Count'];?></td>
<td><?php tr("Number of User/Roles");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".reg("jf/rbac/tables/RoleUsers/table/name")); echo $x[0]['Count'];?></td>
</tr>
<tr align='center'>
<td><?php tr("Active Options");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".reg("jf/options/table/name")); echo $x[0]['Count'];?></td>
<td><?php tr("Number of Roles");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".reg("jf/rbac/tables/Roles/table/name")); echo $x[0]['Count'];?></td>
<td><?php tr("Number of Permissions");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".reg("jf/rbac/tables/Permissions/table/name")); echo $x[0]['Count'];?></td>
<td><?php tr("Number of Role/Permissions");?> : <?php $x=j::SQL("SELECT COUNT(*) AS Count FROM ".reg("jf/rbac/tables/RolePermissions/table/name")); echo $x[0]['Count'];?></td>


</tr>
<!-- Active Requests -->
<tr>
<td colspan='4'>
<h2><?php tr("Popular Requests");?></h2>
<?php
$req=j::SQL("SELECT COUNT(*) AS Count,".reg("jf/session/table/Request")." AS Request FROM ".reg("jf/session/table/name")." GROUP BY Request ORDER BY Count DESC");

if ($req)
{
?>
<table width='95%' border='1' cellspacing='0'>
<thead><tr><th>*</th><th><?php tr("Request");?></th><th><?php tr("Visitors");?></th></tr></thead>
<tbody>
<?php
$n=0;
	foreach ($req as $r)
	{
		if ($n++<5)
		?>
		<tr>
		<td align='center'><?php echo $n?></td>
		<td> &nbsp; <a href='/<?php echo $r['Request']?>'><?php echo $r['Request']?$r['Request']:trr("Home Page");?></a></td>
		<td align='center'><?php echo $r['Count']?></td>
		</tr>
		<?php
	}
?>
</tbody>
</table>
<?php
}
?>
</td>
</tr>
<!-- Active Users -->
<tr>
<td colspan='4'>
<h2><?php tr("Active Visitors");?></h2>
<?php
$req=j::SQL("SELECT S.*,U.* FROM `".reg("jf/session/table/name")."` AS S LEFT JOIN `".jf_Users_Table_Name."` AS U ON (
        	S.`".reg("jf/session/table/UserID")."`=U.`".jf_Users_Table_UserID."`) ORDER BY S.".reg("jf/session/table/AccessCount")." DESC");

if ($req)
{
?>
<table width='95%' border='1' cellspacing='0'>
<thead><tr><th>*</th><th><?php tr("Visitor");?></th><th><?php tr("Visits");?></th><th><?php tr("Login Time");?></th><th><?php tr("Last Access Time");?></th><th><?php tr("Last Request");?></th></tr></thead>
<tbody>
<?php
$n=0;
	foreach ($req as $r)
	{
		if ($n++<5)
		?>
		<tr align='center'>
		<td align='center'><?php echo $n?></td>
		<td align='center'><?php echo $r['Username']?$r['Username']."({$r['IP']})":$r['IP'];?></td>
		<td align='center'><?php echo $r['AccessCount']?></td>
		<td><?php echo date("H:i:s",time()-$r['LoginDate']);?> <?php tr("ago");?></td>
		<td><?php echo date("H:i:s",time()-$r['LastAccess']);?> <?php tr("ago");?></td>
		<td> &nbsp; <a href='/<?php echo $r['CurrentRequest']?>'><?php echo $r['CurrentRequest']?$r['CurrentRequest']:trr("Home Page");?></a></td>
		</tr>
		<?php
	}
?>
</tbody>
</table>
<?php
}
?>
</td>
</tr>

<!-- Critical Logs -->
<tr>
<td colspan='4'><h2><?php tr("Critical Logs");?></h2></td>
<td colspan='4'>


</td>
</tr>
<tr>
<td colspan='8'>
<?php
$Logs=j::SQL("SELECT * FROM `".reg("jf/log/table/name")."` WHERE `".reg("jf/log/table/Severity")."`>=5 ORDER BY `".reg("jf/log/table/Timestamp")."` DESC LIMIT 50");
if (is_array($Logs))
{
$n=0;
    foreach ($Logs as $L)
    {
    	echo "<strong>".++$n.". ".$L['Subject']." (".$L['Severity'].")</strong> ".$L['Data']." <i>(".date("Y-m-d H:i:s",$L['Timestamp']).")</i>".BR;
    }
?>
<form action='../logs/view' onsubmit='return confirm("Are you sure?");' method='post'>
<input type='hidden' name='DelSeverity' value='7' />
<input type='submit' value='Clear All Logs' />
</form>
<?php 
} ?>
</td>
</tr>
</table>
<a href='?action=updateSchema'><?php echo tr("Update database schema from ORM models");?></a>