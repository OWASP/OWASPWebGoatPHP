<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php tr('dir="ltr"');?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
    echo reg("app/title");
?></title>
<link rel="shortcut icon" href="/img/jlogo.png" />
<script src='/script/jquery/132min.js'></script>
<script src='/script/jquery/reflect.js'></script>
</head>
<body>
<?php if (!isset($_GET['noheader'])) { ?>
<style>
body {
	background-color: #0E2E3E;
	margin: 0px;
}

p {
	border: 0px;
	outline: none;
	margin: 0px;
	padding: 0px;
}

a {
	text-decoration: none;
	color: inherit;
}
a:HOVER {
	text-decoration: underline;
}


.top_navitem {
	padding: 4px 4px 0px;
	font-size: 11px;
	margin: 0px;
	border: 0px;
	outline: none;
	-moz-border-radius: 5px;
	cursor: pointer;
}

.top_navitem:HOVER {
	text-decoration: underline;
}
#top_menu {
	height:20px;
}

#top_navitems {
	width: 950px;
	margin: auto;
	text-align: left;
	color: white;
	font-size: 14px;
	padding-top: 3px;
	padding-left: 50px;
}


#body {
	background-color: #FEFEFE;
	min-height: 500px;
	margin: auto;
	padding-top: 0px;
	margin-bottom:10px;
}
span#language {
	<?php tr("float: right;")?>
	padding:2px 5px 2px 5px;
}
span#language a {
	color:#999999;
	font-size:smaller;
	padding:4px;
	text-decoration:none;
}
span#language a:HOVER {
	text-decoration:underline;
}

</style>

<div id="top_menu">
<div id="top_navitems">
<?php
if ($this->Session->UserID)
{
    echo "" . $this->Session->Username() . "";
    ?>
    <a href="/sys/logout" id="username"
	style="font-size: 9px; ">
<?php tr("Sign Out");?> </a>
    <?php
}
else
{
    ?>
    <a href="sys.login"
	style="font-size: 9px; font-family: smallfonts;"><?php tr("Sign
In");?></a>
    <?php
}
?>
<span id='language'>
<?php 
	
		foreach (j::$i18n->Languages as $k=>$l)
		{
			if ($k==j::$i18n->GetActive()) {$currentLang=$l; continue;}
			{?><a href='?lang=<?php echo $k?>'><?php echo $l?></a><?php }
		}
?>	
</span>
</div>

</div>
<div id="body">
<?php }?>







<style>
a[href]  {
	text-decoration:underline;
}

</style>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
<tr><td valign="top" width="200" style="padding-right:15px;">

<ul style="list-style-type: square;text-indent: 0px;margin: 0px;padding-left:16px;font-size:small;">
  <li> <a class="main"><?php tr("Administration");?></a>
		<ul>
  			<li><a href="/sys/panel/dashboard" class="sub"><?php tr("Dashboard");?></a></li>
  			<li><a href="/sys/users/online" class="sub"><?php tr("Online Visitors");?></a></li>
  			<li><a class="sub" href="/sys/logs/view"><?php tr("View Logs");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("Users");?></a>
		<ul>
  			<li><a class="sub" href="/sys/users/add"><?php tr("Add");?></a></li>
  			<li><a class="sub" href="/sys/users/edit"><?php tr("Edit");?></a></li>
  			<li><a class="sub" href="/sys/users/remove"><?php tr("Remove");?></a></li>
  			<li><a class="sub" href="/sys/users/assign"><?php tr("Assign Roles");?></a></li>
  			<li><a class="sub" href="/sys/users/unassign"><?php tr("Unassign Roles");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("RBAC");?></a>
		<ul>
  			<li><a class="sub" href="/sys/rbac/addpermission"><?php tr("Add"); echo " "; tr("Permission");?></a></li>
  			<li><a class="sub" href="/sys/rbac/editpermission"><?php tr("Edit"); echo " "; tr("Permission");?></a></li>
  			<li><a class="sub" href="/sys/rbac/deletepermission"><?php tr("Delete");tr("Permissions");?></a></li>
  			<li><a class="sub" href="/sys/rbac/addrole"><?php tr("Add"); echo " "; tr("Role");?></a></li>
  			<li><a class="sub" href="/sys/rbac/editrole"><?php tr("Edit"); echo " "; tr("Role");?></a></li>
  			<li><a class="sub" href="/sys/rbac/deleterole"><?php tr("Delete"); echo " "; tr("Roles");?></a></li>
  			<li><a class="sub" href="/sys/rbac/assign"><?php tr("Assign");?></a></li>
  			<li><a class="sub" href="/sys/rbac/unassign"><?php tr("Unassign");?></a></li>
		</ul>
  </li>
  <li> <a class="main"><?php tr("Development");?></a>
		<ul>
  			<li><a href="/sys/modules/add" class="sub"><?php tr("Add Module");?></a></li>
  			<li><a class="sub" href="/sys/panel/development/options"><?php tr("View Options");?></a></li>
  			<li><a class="sub" href="/sys/panel/development/registry"><?php tr("View Registry");?></a></li>
  			<li><a href="/sys/panel/development/translate" class="sub"><?php tr("Translate");?></a></li>
		</ul>
  </li>
  
</ul>
</td><td valign="top">
