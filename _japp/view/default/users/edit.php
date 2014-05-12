<?php
if ($this->User)
{
	?>
<style>
label { <?php 
	tr ( "float: left; " );
	?>
	width: 100px;
}
</style>
<form
	onsubmit='
var x1=document.getElementById("Password");
var x2=document.getElementById("Retype");
if (x1.value!=x2.value)
{
	alert("<?php
	tr ( "Retype does not match password!" );
	?>");
	return false;
}
'
	method="post"
><input type='hidden' name='uid'
	value='<?php
	echo $this->User ['ID'];
	?>'
/> <label><?php tr("Username");?>:</label><input type='text' name='Username'
	value='<?php
	echo $this->User ['Username'];
	?>'
/> <br />
<label><?php tr("Password");?>:</label><input type='password' id="Password" name='Password' />
<br />
<label><?php tr("Retype");?>:</label><input type='password' id="Retype" name='Retype' /> <br />
<span style='font-size: small;'><?php tr("Note");?> : <?php tr("leave passwords blank for no change");?></span>
<br />
<span style='font-size: small;'><?php tr("Note");?> : <?php tr("usernames are not case sensitive");?> </span><br />
<input type='submit' value='<?php tr("Apply");?>' /></form>
<br />
<a href="?"><?php tr("Back");?></a>
<?php
}
elseif ($this->Users)
{
	?>
    <?php tr("Select a user to edit");?>:
<table border="1" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
		<?php
	foreach ( $this->Users [0] as $h => $v )
	{
		?><th><?php
		echo $h;
		?></th><?php
	}
	?>
	</tr>
	</thead>
	<tbody>
<?php
	foreach ( $this->Users as $User )
	{
		?><tr>

      <?php
		$n = 0;
		foreach ( $User as $v )
		{
			$n ++;
			?><td><?php
			if ($n == 1)
			{
				$ju = new jURL ( );
				?>
            	<a href="?uid=<?php
				echo $v?>">
            <?php
			}
			echo $v;
			if ($n == 1)
			{
				?></a><?php
			}
			?></td><?php
		}
		?></tr>      
       <?php
	}
	?>
</tbody>
</table>
<div style="font-size: small; text-align: center; margin: 10px;">
<?php
	if ($this->Offset > 0)
	{
		$x = $this->Offset - $this->Limit;
		if ($x < 0) $x = 0;
		?>
<a href="?offset=<?php
		echo $x?>&limit=<?php
		echo $this->Limit?>">&lt; <?php tr("Previous");?></a>
<?php
	}
	?>
<span style="padding-left: 10px; padding-right: 10px;"> <?php tr("Show");?> <a
	href="?offset=<?php
	$this->Offset?>&limit=10"
>10</a> <a href="?offset=<?php
	$this->Offset?>&limit=20">20</a> <a
	href="?offset=<?php
	$this->Offset?>&limit=50"
>50</a> <a href="?offset=<?php
	$this->Offset?>&limit=100">100</a> <a
	href="?offset=<?php
	$this->Offset?>&limit=100000"
>All</a> <?php tr("items");?> </span>
    <?php
	if ($this->Offset + $this->Limit < $this->Count)
	{
		?>
<a
	href="?offset=<?php
		echo $this->Offset + $this->Limit?>&limit=<?php
		echo $this->Limit?>"
><?php tr("Next");?> &gt;</a>
<?php
	}
	?>
    
</div>
<?php
} //if user list
?>