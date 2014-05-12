<?php
//	    	define ("Permission",$Permission);
	header("HTTP/1.1 401 Authentication Required",false);
	?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>401 Not Authorized</title>
</head><body>
<h1>Not Authorized</h1>
<p>You are not authorized to access this resource, i.e you don't have the sufficient permissions
to access <b><?php echo HttpRequest::File();?></b> at <b><?php echo HttpRequest::URL();?></b></p>
</body></html>
<?

?>