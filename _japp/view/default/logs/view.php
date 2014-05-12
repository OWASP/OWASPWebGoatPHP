<?php
?>
<script>
$(function(){
	$("#PresidentCheckbox").bind("click",function(){
		$(".LogCheckbox").attr("checked",$("#PresidentCheckbox").attr("checked"));
	});
});
</script>
<form method='post'>
	<table width='100%' border='1' cellspacing="0" cellpadding='1'>
	<thead>
	<tr>
		<th><input type='checkbox' id='PresidentCheckbox' /></th>
		<th>ID</th>
		<th>Subject</th>
		<th>Data</th>
		<th>Severity</th>
		<th>User</th>
		<th>SessionID</th>
		<th>Time</th>
	</tr>
	</thead>
	<tbody>
	
<?php
if (is_array($this->Logs))
foreach($this->Logs as $L)
{
	?>
	<tr align='center'>
	<td><input class='LogCheckbox' type='checkbox' name='Log[]' value='<?php echo $L['ID'];?>' /></td>
	<?php
	foreach ($L as $k=>$v)
	{
		if ($k=="Timestamp")
			$v=date("Y-m-d H:i:s",$v);
		echo "<td>$v</td>";
	}
	?>
	</tr>
	<?php
}

?>
	</tbody>	
	</table>
<input type='submit' value='Delete Selected' />
</form>

<form method='post'>
<input type='submit' value='Delete' />
<input size='3' type='text' value='<?php echo $this->Limit; ?>' name='DelLimit' /> items
starting from item <input size='3 type='text' value='<?php echo $this->Offset; ?>' name='DelOffset' />
</form>

<form method='post' onsubmit='return confirm("Are you sure?");'>
<input type='submit' value='Delete' />
all with severity less than or equal to <input type='text' size='2' value='4' name='DelSeverity' />
</form>

<form method='post' onsubmit='return confirm("Are you sure?");'>
<input type='submit' value='Delete' />
all with subject <input type='text' value='GeneralError' name='DelSubject' />
</form>


<div style='text-align:center;'>
<form method='get'>
Show <input type='text' size='3' name='lim' value='<?php echo $this->Limit; ?>' /> 
items
starting from <input type='text' size='3' name='off' value='<?php echo $this->Offset+$this->Limit; ?>' /> 
<input type='submit' value='Go' />

</form>
</div>
<?php
?>

