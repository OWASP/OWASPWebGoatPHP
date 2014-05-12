<?php if (jf::$RunMode->IsEmbed()) return;?>
<div id='Tracker' class='Footer'>
<?php
$x=jf::$Profiler->Timer();
printf("This page was generated in %.4f seconds (%.4f DB, %.4f PHP) with %d database queries.",
    $x,jf::db()->QueryTime(),$x-jf::db()->QueryTime() ,jf::db()->QueryCount());
?>
</div>
<div id='Copyright' class='Footer'>
<a href='<?php echo constant("SiteRoot") ?>'><?php echo jf_Application_Title; ?></a>
powered by
<a href='http://jframework.info/' style="">
<img title='jframework 4' src="<?php echo (jf::url());?>/img/jlogo.png" width=16 height=16  style="
text-decoration:none;
outline:none;
border: 0 solid;
vertical-align: middle;"/>
 </a>
</div>
</body>
</html>