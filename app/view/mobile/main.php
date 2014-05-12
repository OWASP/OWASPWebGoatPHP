<?php
$m=new UseragentMobilePlugin();
//echo "Hi this is intended to be the mobile view of jFramework! You're on ".$m->IsMobileUserAgent().BR;
?>
<h1>jFramework Powered Mobile Interface</h1>
<p>
Sorry, but the website you're trying to visit has enabled different presentation for mobile devices,
but is lacking a duplicate version of the website for the mobile.
</p>

You are visiting from : <strong><?php echo $m->IsMobileUserAgent();?></strong>
<hr/>
To visit this website in non-mobile view, 
<a href='?mobileView=off'>click here</a> 

