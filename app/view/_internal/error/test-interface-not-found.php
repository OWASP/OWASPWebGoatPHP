<?php
// $Request, $Module
// you can use the constants at above
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>Test Not Found</title>
</head><body>
<h1>Test Not Found</h1>
<p>The requested test does not exist: <b><?php echo $Request?></b>
at <b><?php echo HttpRequest::URL()?></b></p>
</body></html>

