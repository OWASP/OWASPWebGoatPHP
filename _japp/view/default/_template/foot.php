</td></tr>
</table>



<?php if (!isset($_GET['noheader'])) { ?>


</div>
<div id='Tracker' class='Footer' <?php tr('dir="ltr"')?> style="text-align:center;font-size:Smaller;color:#555555;">
<?php
$x=$this->Tracker->PageLoadTime();
printf(trr("This page was generated in %.4f seconds (%.4f DB time, %.4f PHP time) with %d database queries and consumed %.4f MB of memory."), 
    $x,$this->DB->QueryTime,$x-$this->DB->QueryTime ,$this->DB->QueryCount,memory_get_peak_usage()/1024.0/1024);
?>
</div>
<div id='Copyright' class='Footer' dir="ltr" style="text-align:center;font-size:Smaller;color:#555555;margin-bottom:5px;">
<a href='<?php echo constant("SiteRoot") ?>'><?php echo WHOAMI ?></a>
<?php tr("powered by")?>
<a href='http://jframework.info/' style="">
<img title='jFramework' src="/img/jlogo.png" width=16 height=16  style="
text-decoration:none;
outline:none;
border: 0 solid;
vertical-align: middle;"/>
 </a>
<?php }//end noheader ?>
</body>
</html>
