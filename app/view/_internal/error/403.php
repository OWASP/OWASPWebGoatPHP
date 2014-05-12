<?php
#authenticated but can not access the page
header("HTTP/1.1 403 Forbidden",false);
	?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You are not allowed to access this page.</p>
<hr/><span style='font-size:smaller;'><?php echo \jf\WHOAMI;?></span>
</body></html>
<?

?>