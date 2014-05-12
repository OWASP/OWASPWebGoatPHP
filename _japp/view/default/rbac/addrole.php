<?php
if ($this->Result)
{
    ?>
* Role
<b><?php
    echo $_POST['Title']?></b>
with ID
<b><?php
    echo $this->Result?></b>
added.
<hr />
<?php
}
?>
<style>
#Roles {
	border: 3px double;
	padding: 5px;
	margin-top: 5px;
	margin-bottom: 10px;
}
.id {
	font-size:small;
}
</style>
<form method="post">Select a role as the parent to the new
role:
<div id="Roles" >
<?php
foreach ($this->Roles as $P)
{
    if ($this->Result && $this->Result == $P['ID'])
        echo "<font color='red'>";
    echo str_repeat("&nbsp;", $P['Depth'] * 5);
    ?><input type='radio' class="pid" name="pid" value='<?php
    echo $P['ID']?>'
	id='p<?php
    echo $P['ID']?>'
	<?php
    if (isset($_POST['pid']) && $_POST['pid'] == $P['ID'])
        echo "checked='checked' ";
    elseif ($P['ID'] == 0)
        echo "checked='checked' "?> /> 
	<?php
    ?> <b><span class="title" id="title_<?php
    echo $P['ID']?>"><?php
    echo $P['Title']?></span></b>
(<span class="id" id="id_<?php
    echo $P['ID']?>"><?php
    echo $P['ID']?></span>)
: <span class="description" id="description_<?php
    echo $P['ID']?>"><?php
    echo $P['Description'];
    ?></span>
    <?php
    if ($this->Result && $this->Result == $P['ID'])
        echo "</font>";
    ?>
	
	<br />
<?php
}
?>
</div>
<b>New Role:</b><br />
Title: <input type='text' id="in_title" name='Title'> Description: <input type='text'
	size="40" id="in_title" name='Description' 2/> <input type='submit' value='Add' /></form>
<script>
	$(".pid").click(function()
			{
				$("#in_title").focus();
				
				
			});
			

</script>
	