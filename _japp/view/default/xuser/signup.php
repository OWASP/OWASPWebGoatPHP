<?php
?>
<style>
#signupform label { 
	width:100px;
	float:left;
	cleart:left;
}
#signupform  input[type="text"],input[type="password"] {
	width:150px;
	
}

#signupform #rememberme {
	text-align:center;
}
#signupform {
	
	text-align:center;
	width:300px;
	border:3px double black;
	padding:10px;
	margin:5px;
	margin:auto;
}
#signupform .error {
	color:white;
	font-weight:bold;
	margin:5px;
	background-color:#FFAAAA;
	border:1px dashed red;
	padding:2px;
}
#signupform .success {
	color:black;
	font-weight:bold;
	margin:5px;
	background-color:#AAFFAA;
	border:1px dashed darkgreen;
	padding:2px;
}
</style>
<form id="signupform" method="post">
	<strong>Signup to <?php echo constant("jf_Application_Title");?></strong>
	<br/>
	<br/>
	
	<label>Username :</label>
	<input type='text' value="" name="Username" />
	<br/>
	<label>Password :</label>
	<input type="password" name="Password" />
	<br/>
	<label>Confirm :</label>
	<input type="password" name="Confirm" />
	<br/>
	<label>Email :</label>
	<input type="text" name="Email" />
	<br/>
	
	
	<br/>
	<input type="submit" value="Signup" />
	<input type="button" value="Back" onclick="history.back()" />
	
	<?php 
	if (isset($this->Error)):
	?>
	<div class='error'>
	<?php echo $this->Error;?>
	</div>	
	<?php elseif (isset($this->Success)):?>
	<div class='success'>
	<?php if (is_string($this->Success)) echo $this->Success; else {?>
	Signup successful. Check your email for activation link.
	<?php } ?>
	</div>
	<?php endif;?>
	</form>


