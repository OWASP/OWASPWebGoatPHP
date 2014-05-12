<?php
?>
<style>
#resetform label { 
	width:150px;
	float:left;
	cleart:left;
	text-align:left;
}
#resetform  input[type="text"],input[type="password"] {
	width:150px;
	
}

#resetform #rememberme {
	text-align:center;
}
#resetform {
	
	text-align:center;
	width:300px;
	border:3px double black;
	padding:10px;
	margin:5px;
	margin:auto;
}
#resetform .error {
	color:white;
	font-weight:bold;
	margin:5px;
	background-color:#FFAAAA;
	border:1px dashed red;
	padding:2px;
}
#resetform .success {
	color:black;
	font-weight:bold;
	margin:5px;
	background-color:#AAFFAA;
	border:1px dashed darkgreen;
	padding:2px;
}
</style>
<form id="resetform" method="post">
	<strong>Password Recovery</strong>
	<br/>
	<br/>
	<?php if (isset($this->TempPass)):?>
	<label>Username : </label>
	<input type='text' value='<?php exho ($this->Username);?>' name='Username' /> 
	<br/>

	<label>Current Password : </label>
	<input type='text' value='<?php exho ($this->TempPass);?>' name='TempPass' /> 
	<br/>
	
	<label>New Password : </label>
	<input type='password' value='' name='Password'/> 
	<br/>
	
	<label>Retype : </label>
	<input type='password' value='' name='Retype'/> 
	<br/>
	
	<br/>
	<input type='submit' value='Change Password' />
	
	
	<?php else:?>
	<label>Email :</label>
	<input type='text' value="" name="Email" />
	<br/>
	
	<br/>
	<input type="submit" value="Recover" />
	<?php endif;?>
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
	Instructions to reset your password were sent to your inbox.
	<?php } ?>
	</div>
	<?php endif;?>
	</form>


