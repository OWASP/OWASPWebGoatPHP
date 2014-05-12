<?php

?>
<style>
label {
    width:150px;
    <?php tr("float:left;");?>
}

</style>
<?php tr("Add new user");?>
<hr/>
<form method="post" onsubmit="return check();">
<label><?php tr("Username");?>:</label>
<input type='text' name="Username" id="Username"/>
<br/>
<label><?php tr("Password");?>:</label>
<input type='password' name="Password" id="Password"/>
<br/>
<label><?php tr("Retype");?>:</label>
<input type='password' name="Retype" id="Retype"/>
<br/>

<input type='submit' value="<?php tr("Register");?>"/>
</form>
<p>
<b><?php tr("Note");?>:</b> <?php tr("make sure to assign a role to the user you create if you're using RBAC.");?>
</p>
<script>
function check()
{
	y=document.getElementById("Username");
	if (y.value=="") 
	{
		alert("<?php tr("Enter a username!");?>");
		return false;
	}
	x=document.getElementById("Password");
	x2=document.getElementById("Retype");
	if (x.value!=x2.value)
	{
		alert("<?php tr("Invalid retype!")?>");
		return false;
	}
	return true;
}

</script>