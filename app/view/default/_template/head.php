<?php if (jf::$RunMode->IsEmbed()) return; ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->Title()?:jf_Application_Title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="author" content="Shivam Dixit">
        <link rel="shortcut icon" type="image/png" href="<?php echo jf::url().'/images/favicon.ico'; ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/bootstrap.min.css'?>">
        <link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/style.css'?>">
        <link rel="stylesheet" href="<?php echo jf::url()?>/style/reveal.css">
        <script type="text/javascript" src="<?php echo jf::url()?>/script/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="<?php echo jf::url()?>/script/bootstrap.min.js"></script>
    </head>
    <body>