<?php
// $Request, $Service, $Params are usable
// you can use the constants at above
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>Service Not Found</title>
</head><body>
<h1>Service Not Found</h1>
<p>The requested service does not exist: <b><?php echo $Request?></b>
at <b><?php echo HttpRequest::URL()?></b></p>
</body></html>


