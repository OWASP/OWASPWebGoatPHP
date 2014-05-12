<?php
//$File is usable here
// this is invoked when jFramework tried to present a file which does not exist (as a resource)
		header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>jFramework could not find the requested URL: <b><?php echo htmlspecialchars(jf::$Request) ?></b>.</p>
</body></html>
<?

?>