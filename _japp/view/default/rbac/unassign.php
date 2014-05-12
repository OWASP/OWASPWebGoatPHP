<?php
if ($this->Result)
    echo $this->Result . BR;
?>
<style>
tr {
	cursor: pointer;
}
</style>
<form method="post">
<table border='1' cellspacing='0' cellpadding='1'
	style="text-align: center;" width="100%">
	<thead>
		<tr>
			<th><input type='checkbox' id='a_top'
				onclick='
$(".a").attr("checked",$("#a_top").attr("checked"));
' /></th>
			<th><a href="?sort=RoleID">Role ID</a></th>
			<th><a href="?sort=RoleTitle">Role Title</a></th>
			<th><a href="?sort=AssignmentDate">Assign Date</a></th>
			<th><a href="?sort=PermissionTitle">Permission Title</a></th>
			<th><a href="?sort=PermissionID">Permission ID</a></th>
		</tr>
	</thead>
	<tbody>

<?php
if ($this->Assignments)
    foreach ($this->Assignments as $P)
    {
        ?>
	<tr class="tra"
			id='a<?php
        echo $P['RoleID'] . "_" . $P['PermissionID']?>'>
			<td>
			<?php
        if ($P['RoleID'] != 0 or $P['PermissionID'] != 0)
        {
            ?>
			<input class="a" name="a[]" type='checkbox'
				value='<?php
            echo $P['RoleID'] . "_" . $P['PermissionID']?>'
				id='a<?php
            echo $P['RoleID'] . "_" . $P['PermissionID']?>' /><?php
        }
        ?></td>
			<td class="role"><?php
        echo $P['RoleID']?></td>
			<td class="role" title='<?php
        echo $P['RoleDescription']?>'><?php
        echo $P['RoleTitle']?></td>
			<td><?php
        echo $P['AssignmentDate']?></td>
			<td class="permission"
				title='<?php
        echo $P['PermissionDescription']?>'><?php
        echo $P['PermissionTitle']?></td>
			<td class="permission"><?php
        echo $P['PermissionID']?></td>
		</tr>
<?php
    }
?>

</tbody>
</table>
<div style='font-size: small; text-align: Center;'><?php
echo count($this->Assignments)?> items out of <?php
echo $this->Count?></div>
<br />
<input type='submit' value='Unassign' /></form>

<div style='text-align: center; margin-top: 2px; font-size: small;'>
<form action="?<?php
echo $_GET['sort']?>&">Show <input type='text' name='limit' size='3'
	value="<?php
echo $_GET['limit'] ? $_GET['limit'] : "20"?>" /> items starting from <input
	type='text' size='4' name='offset'
	value="<?php
echo $_GET['offset'] + $_GET['limit']?>" /> <input type='submit'
	value='Go' /></form>
</div>




<script>
$(".tra").click(function()
		{
		x=$("input#"+$(this).attr("id"));
	x.attr("checked",!x.attr("checked"));
		});

$(".a").click(function(e){e.stopPropagation();});
</script>