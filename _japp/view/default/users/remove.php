<?php
if ($this->Users)
{
    ?>
Check all the users you want to remove:
<form method="post" onsubmit='
	return confirm("Are you sure you want to remove selected users from jFramework?\nYou will not be able undo this later!");
'>
<table border="1" cellspacing="0" cellpadding="2" align="center" >
	<thead>
		<tr>
<th><input type="checkbox" id='sellord' onclick="
$('.sel').attr('checked',$('#sellord').attr('checked'));
"/></th>
		<?php
    foreach ($this->Users[0] as $h => $v)
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
    foreach ($this->Users as $User)
    {
        ?><tr>

      <?php
        $n = 0;
        foreach ($User as $v)
        {
            $n ++;
            ?><td><?php
            if ($n == 1)
            {
                ?>
                	<input type="checkbox" class="sel" name="sel[]"
				value="<?php echo $v;
                ?>" /></td><td> <?php
            }
            echo $v;
            ?></td><?php
        }
        ?></tr>      
       <?php
    }
    ?>
</tbody>
</table>
<br/>
<input type='submit' value='Remove' />
</form>

<div style="font-size: small; text-align: center; margin: 10px;">
<?php
    if ($this->Offset > 0)
    {
        $x = $this->Offset - $this->Limit;
        if ($x < 0)
            $x = 0;
        ?>
<a
	href="?offset=<?php
        echo $x?>&limit=<?php
        echo $this->Limit?>">&lt; Previous</a>
<?php
    }
    ?>
<span style="padding-left: 10px; padding-right: 10px;"><?php tr("Show");?><a
	href="?offset=<?php
    $this->Offset?>&limit=10">10</a> <a
	href="?offset=<?php
    $this->Offset?>&limit=20">20</a> <a
	href="?offset=<?php
    $this->Offset?>&limit=50">50</a> <a
	href="?offset=<?php
    $this->Offset?>&limit=100">100</a> <a
	href="?offset=<?php
    $this->Offset?>&limit=100000">All</a> items </span>
    <?php
    if ($this->Offset + $this->Limit < $this->Count)
    {
        ?>
<a
	href="?offset=<?php
        echo $this->Offset + $this->Limit?>&limit=<?php
        echo $this->Limit?>">Next &gt;</a>
<?php
    }
    ?>
    
</div>
<?php
} //if user list
?>