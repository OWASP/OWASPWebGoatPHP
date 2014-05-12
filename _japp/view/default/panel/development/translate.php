<style>
input[name='translation[]'],textarea[name='translation[]'] {
	width:95%;
	margin:2px;
	border:1px solid #AAAAAA;
	min-height:22px;
}
</style>

<form method='get' name='target' >
Choose your desired target language:
<select name='target' >
<?php 
	if (count($this->Languages))
		foreach ($this->Languages as $k=>$v)
		{	
			if ($this->Target==$k)
				$Sel=" selected='selected' ";
			else
				$Sel="";
			echo "<option {$Sel} value='{$k}'>{$v}</option>\n";
		}

?>
</select>
<input type='submit' value='Go' />
</form> 

<h2>Translation list</h2>
<form method='post'>
<?php 
if ($this->Affected)
	echo $this->Affected." phrases affected.".BR;
if (is_array($this->Phrases))
{
	?>
	
	<table border='0' width='100%' cellspacing='0' cellpadding='0'>
	<thead>
	<tr>
		<th width='10'>
		<input type="checkbox"  id='listCheck' />
		</th>
		<th width='60'>
		ID
		</th>
		<th width='50'>Pivot</th>
		<th width='40%'>Phrase</th>
		<th>Translation</th>
		<th width='20'>Target</th>
	</tr>
	</thead>
	<tbody>
	
	<?php 
foreach ($this->Phrases AS $P)
{
	?>
	<tr align='center' valign='top'>
		<td>
		<input type="checkbox" value='<?php echo $P['PID1'];?>' name='list[]' />
		</td>
		<td>
		<input type='hidden' value='<?php echo $P['PID1'];?>' name='pid1[]' />
		<input type='hidden' value='<?php echo $P['PID2'];?>' name='pid2[]' />
		<a href='?pid=<?php echo ($P['PID1']);?>&target=<?php echo $this->Target;?>'
		title='click here to see the translation set of this phrase'>
		<?php echo ($P['PID1']);?></a>
		
		</td>
		<td><?php echo htmlspecialchars($P['Pivot']);?></td>
		<td style='border-top:1px solid gray;'><?php echo htmlspecialchars($P['Phrase']);?></td>
		<td><?php 
		$Data=($P['Translation']);
		if ($P['PID2']==null)
			$style=" style='background-color:#EEEEEE;' ";
		else
			$style="";
			if (strpos($P['Phrase'],"\n"))
			{
				$rows=substr_count($P['Phrase'],"\n")+1;
				echo "<textarea name='translation[]' {$style} rows='{$rows}'>{$Data}</textarea>";
			}
			else
				echo "<input type='text' name='translation[]' {$style} value='{$Data}' />";
		?></td>
		<td><?php echo $this->Target;?></td>
	</tr>
	<?php 
}
?>
	</tbody>
	</table>
<?php 
}
?>
<span style='float:right;'>
<input type='submit' value='Save' name='action'/>
</span>
<span style='float:left;'>
<input type='submit' name='action' value='Delete Selected Translations' />
<input type='submit' name='action' value='Delete Selected Translations With Original Phrase' />
</span>

</form>


	
<script>
$("#listCheck").click(function(){
	$("input[name='list[]']").attr("checked",$(this).val());
});

</script>