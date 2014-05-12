<?php
	header("HTTP/1.1 401 Authentication Required",false);
// 	header("WWW-Authenticate: Basic realm='".constant("jf_Application_Name")."'");
	?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>401 Not Authorized</title>
</head><body>
<h1>Not Authorized</h1>
<p>You are not authorized to access this resource, probably because you haven't logged into the system yet.</p>
<hr/>
<span style='font-size:smaller;'><?php echo \jf\WHOAMI;?></span>
</body></html>
<?

?>