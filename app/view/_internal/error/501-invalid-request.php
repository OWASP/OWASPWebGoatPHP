<?php
//$Request,$RequestType is usable here
// this is invoked when jFramework receives an unknown request type! (like test.main)
		header("HTTP/1.0 501 Not Found");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>501 Not Implemented</title>
</head><body>
<h1>Not Implemented</h1>
<p>jFramework does not understand requests of type <b><?php echo $RequestType?></b>,
These types of requests are not implemented on this jFramework Application. 
You submitted the reuqest:
<br/>
<b><?php echo $Request?></b>
</p>
</body></html>
<?

?>