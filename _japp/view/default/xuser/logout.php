
<div id="logout_container" style="margin:auto;text-align:Center;">
<?php
if ($this->Username)
{
    ?>
<strong><?php
    echo $this->Username?></strong>
logged out successfully. 
<?php
}
else
{
    ?>
	You must be already logged in to be able to log out!
<?php
}
?>
<br />

You will be redirect in <span id="redirect_timer">5</span> seconds ...
</div>
<script>
function countDown()
{

	var x=document.getElementById("redirect_timer");
	var val=(x.innerHTML*1);
	x.innerHTML=val-1;
	
	if (val==1)
		document.location="<?php echo $this->Return?>";
	else 
		setTimeout(countDown,1000);
}

setTimeout(countDown,1000);

</script>