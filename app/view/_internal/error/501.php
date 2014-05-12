<?php
// this is invoked when jframework receives an unknown request type! )
header("HTTP/1.1 501 Not Implemented");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>501 Not Implemented</title>
</head><body>
<h1>Not Implemented</h1>
<p>The type of request is not implemented.</p>
<hr/><span style='font-size:smaller;'><?php echo \jf\WHOAMI;?></span>
</body></html>
<?

?>