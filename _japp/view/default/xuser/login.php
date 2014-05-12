<?php
?>
<style>
#loginform label { 
	width:100px;
	float:left;
	cleart:left;
}
#loginform  input[type="text"],input[type="password"] {
	width:150px;
	
}

#loginform #rememberme {
	text-align:center;
}
#loginform {
	
	text-align:center;
	width:300px;
	border:3px double black;
	padding:10px;
	margin:5px;
	margin:auto;
}
#loginform .error {
	color:white;
	font-weight:bold;
	margin:5px;
	background-color:#FFAAAA;
	border:1px dashed red;
	padding:2px;
}
#loginform .success {
	color:black;
	font-weight:bold;
	margin:5px;
	background-color:#AAFFAA;
	border:1px dashed darkgreen;
	padding:2px;
}
</style>
<form id="loginform" method="post">
	<strong>Login to <?php echo constant("jf_Application_Title");?></strong>
	<br/>
	<br/>
	
	<label>Username :</label>
	<input type='text' value="<?php echo $this->Username?>" name="Username" />
	<br/>
	<label>Password :</label>
	<input type="password" name="Password" />
	<br/>
	<div id="rememberme"> 
	<input type="checkbox" value="yes" name="Remember" 
		title='checking this will make you automatically login everytime you visit this page for one month unless you logout'/> Remember me on this computer
	</div>
	
	
	<input type="submit" value="Login" />
	<input type="button" value="Back" onclick="history.back()" />
<?php if (jf::CurrentUser()) {?>
	<br/><a style="font-size:small" href="<?php echo jf::url();?>/sys/xuser/logout?return=<?php echo urlencode(jf::url());?>/sys/xuser/login">Sign in as a different user</a>
	<br/>
<?php } ?>
	
	<?php 
	if (isset($this->Error)):
	?>
	<div class='error'>
	<?php echo $this->Error;?>
	</div>	
	<?php elseif (isset($this->Success)):?>
	<div class='success'>
	Successfully logged in.
	</div>
	<?php endif;?>
	</form>
