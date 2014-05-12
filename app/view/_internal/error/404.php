<?php
header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The request resource <b><?php exho (jf::$Request) ?></b> not found.</p>
<hr/>
<span style='font-size:smaller;'><?php echo \jf\WHOAMI;?></span>
</body></html>
<?

?>