<?php
if ($this->Result)
{
	?>
    A total number of <?php
	echo $this->Result?> assignments where made between <?php
	echo count ( $_POST ['uid'] )?> users
    and <?php
	echo count ( $_POST ['rid'] )?> roles.
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
<form method="post">Select roles you want to assign:
<div id="Roles">
<?php
foreach ( $this->Roles as $P )
{
	echo str_repeat ( "&nbsp;", $P ['Depth'] * 5 );
	?><input class="rid" type='checkbox' name="rid[]"
	value='<?php
	echo $P ['ID']?>' id='r<?php
	echo $P ['ID']?>' />
	<b><span class="title" id="title_<?php
	echo $P ['ID']?>"><?php
	echo $P ['Title']?></span></b> (<span class="id"
	id="id_<?php
	echo $P ['ID']?>"><?php
	echo $P ['ID']?></span>) :
	<span class="description"
	id="description_<?php
	echo $P ['ID']?>"><?php
	echo $P ['Description'];
	?></span><br />
<?php
}
?>
</div>
<span style='font-size: smaller;'>Note: do not select descendants of a
role if its own is selected.</span> <br />
<br />
Now select users you want your selected roles to be assigned to : <br />
<?php
foreach ( $this->Users as $U )
{
	?>
        <input type='checkbox' class='uid' name='uid[]'
	value='<?php
	echo $U [jf_Users_Table_UserID]?>' /><?php
	echo $U [reg("jf/users/table/Username")]?> <span class="id">(<?php
	echo $U [jf_Users_Table_UserID]?>)</span>
<br />
        <?php
}

?>
<div style='font-size: small;'><a href='#'
	onclick='
	$(".uid").attr("checked",true);
'>All</a> | <a href='#' onclick='
	$(".uid").attr("checked",false);
'>None</a></div>
<br />
<div style='font-size: small;'><input type='checkbox' name='Replace'
	value='1' />Replace existing assignments (will only update
AssignmentDate)</div>
<br />
<input type='submit' value='Assign' /></form>
<span style='font-size: smaller;'>Note: All selected roles would be
assigned to all selected users.</span>
<br />
