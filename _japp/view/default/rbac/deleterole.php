<?php
if ($this->Result)
{
    ?>
A total number of <?php echo $this->Result ?> roles where deleted <?php 
if ($_POST['Recursive']) echo " with their descendants"?>.

<?php echo BR.$this->Error?>
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
	font-size: small;
}
</style>
<form method="post">Select roles you want to delete:
<div id="Roles" >
<?php
foreach ($this->Roles as $P)
{
    if ($this->Result && $this->Result == $P['ID'])
        echo "<font color='red'>";
    echo str_repeat("&nbsp;", $P['Depth'] * 5);
    ?><input class="pid" type='checkbox' name="pid[]" value='<?php
    echo $P['ID']?>'
	id='p<?php
    echo $P['ID']?>' 
    <?php if ($P['ID']==0) echo "disabled='disabled' title='You can not delete root!'"?>/>
    <b><span class="title" id="title_<?php
    echo $P['ID']?>"><?php
    echo $P['Title']?></span></b> (<span class="id"
	id="id_<?php
    echo $P['ID']?>"><?php
    echo $P['ID']?></span>) : <span class="description"
	id="description_<?php
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
<input id="Recursive" type='checkbox' name='Recursive' value="1"/>Delete all descendants of selected nodes as well<br/>
<input type='submit' value='Delete' /></form>
<span style='font-size:smaller;'>Warning: You can not undo this operation! By clicking the delete button roles would be instantly deleted.</span>
<script>
	$("#Recursive").click(function()
			{
				return confirm("Selecting this will cause a lot of nodes to be removed if you select main parent nodes, are you sure?");
				
			});
</script>
