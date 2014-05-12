<?php 
if ($this->Result)
{
    ?>
    A total number of <?php echo $this->Result ?> assignments where made.
    <hr/>
    <?php 
}
?>

<style>
#Roles {
	border: 3px double;
	padding: 5px;
	margin-top: 5px;
	margin-bottom: 10px;
}

#Permissions {
	border: 3px double;
	padding: 5px;
	margin-top: 5px;
	margin-bottom: 10px;
}

#p0 * {
	width: 100px;
	height: 100px;
}

.id {
	font-size: small;
}
</style>
<form method="post">
<h3 style="margin: 0px;">Permissions</h3>
Select permissions you want to assign to roles:
<div id="Permissions" >
<?php
foreach ($this->Permissions as $P)
{
    echo str_repeat("&nbsp;", $P['Depth'] * 5);
    ?><input class="pid" type='checkbox' name="pid[]"
	value='<?php
    echo $P['ID']?>' id='p<?php
    echo $P['ID']?>' /> <b><span class="title"
	id="ptitle_<?php
    echo $P['ID']?>"><?php
    echo $P['Title']?></span></b> (<span class="id"
	id="pid_<?php
    echo $P['ID']?>"><?php
    echo $P['ID']?></span>) : <span class="description"
	id="pdescription_<?php
    echo $P['ID']?>"><?php
    echo $P['Description'];
    ?></span>
	<br />
<?php
}
?>
</div>




<h3 style="margin: 0px;">Roles</h3>
Select roles you want your selected permissions to be assigned to:
<div id="Roles">
<?php
foreach ($this->Roles as $P)
{
    echo str_repeat("&nbsp;", $P['Depth'] * 5);
    ?><input class="rid" type='checkbox' name="rid[]"
	value='<?php
    echo $P['ID']?>' id='r<?php
    echo $P['ID']?>' /> <b><span class="title"
	id="rtitle_<?php
    echo $P['ID']?>"><?php
    echo $P['Title']?></span></b> (<span class="id"
	id="rid_<?php
    echo $P['ID']?>"><?php
    echo $P['ID']?></span>) : <span class="description"
	id="rdescription_<?php
    echo $P['ID']?>"><?php
    echo $P['Description'];
    ?></span>
	<br />
<?php
}
?>
</div>
<input type='checkbox' name='Replace' value='1' /> Replace already existing assignments (would update their dates)
<br/>
<input type='submit' value='Assign' /></form>
<span style='font-size: smaller;'> <b>Note:</b> All selected permissions
would be assigned to all selected roles. If you want to assign a
permission to a role, just select a single permission and a single role.</span>
<br />
<br />
<span style='font-size: smaller;'> <b>Important Note:</b> Assigning a
parent permission to a role also means assigning all descendants of that
permission to that role, and also means assigning all those permissions
to all descendants of that role. So keep in mind not to check any role
with its descendants or any permission with its descendants, just select
the parents.</span>