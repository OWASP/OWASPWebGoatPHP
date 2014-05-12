<?php
?>
<style>
label { 
	width:100px;
	<?php tr("float:left;");?>
}
input[type="text"],input[type="password"] {
	width:150px;
	
}

#remember_container {
	text-align:center;
}
#login_container {
	
	margin:auto;
	text-align:center;
	width:300px;
	
}
form#login {
	border: 2px ridge;
	padding:10px;
	margin:5px;
	}

</style>
<div id="login_container" <?php tr("dir='ltr'");?>>
<form id="login" method="post">
	<strong><?php tr("Login to");?> <?php echo reg("app/title");?></strong>
	<br/>
	<?php if (isset($this->Result) and !$this->Result)
	{
	    ?><span style="color:red">Invalid credentials</span><?php
	    $this->Username=$_POST["Username"]; 
	}
	?>
	<br/>
	
	<label><?php tr("Username");?> :</label>
	<input type='text' value="<?php echo $this->Username?>" name="Username" />
	<br/>
	<label><?php tr("Password");?> :</label>
	<input type="password" name="Password" />
	<br/>
	<div id="remember_container"> 
	<input type="checkbox" value="yes" name="Remember" /> <?php tr("Remember me on this computer");?>
	</div>
	
	
	<input type="submit" value="<?php tr("Login");?>" />
	<input type="button" value="<?php tr("Back");?>" onclick="history.back()" />
<?php if ($this->UserID) {?>
	<br/><a style="font-size:small" href="/uesr/logout?return=/user/login"><?php tr("Sign in as a different user");?></a>
<?php } ?>
	
	</form>

</div>