
<div id="logout_container" style="margin:auto;text-align:Center;">
<?php
if ($this->Username)
{
    ?>
<strong><?php
    echo $this->Username?></strong>
<?php tr("logged out successfully.");?> 
<?php
}
else
{
    ?>
	<?php tr("You must be already logged in to be able to log out!");?>
<?php
}
?>
<br />

<?php tr("You will be redirect in ");?> <span id="redirect_timer">5</span> <?php tr("seconds");?>...
</div>
<script>
function countDown()
{
	
	x=$("#redirect_timer").text();
	$("#redirect_timer").text(x*1-1);
	if (x==1)
		document.location="<?php echo $this->Return?>";
	else 
		setTimeout(countDown,1000);
}

setTimeout(countDown,1000);

</script>