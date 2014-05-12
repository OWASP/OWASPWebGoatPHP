<table border="1" cellpadding="0" cellspacing="0" width="100%">
<?php
if ($this->Sessions)
{
?>
<thead>
<tr>
<?php 
foreach ($this->Sessions[0] as $k=>$v)
{
    ?><th><?php echo $k?></th><?php
}
?>
</tr>
</thead>
<?php
foreach ($this->Sessions as $Session)
{
    ?>
    <tr>
    <?php 
        foreach($Session as $v)
        {
        ?>
        	<td align="center"><?php echo $v;?></td>
        <?php             
        }
    ?>
    </tr>
    <?php 
}
}
?>
</table>
<div id="notes" style="font-size:small;text-align:center;">
* <?php tr("Empty UserID means this is a visitor which has not logged in with");?>
</div>