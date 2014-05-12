<?php if (jf::$RunMode->IsEmbed()) return; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Title()?:jf_Application_Title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="author" content="Shivam Dixit">
		<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/bootstrap.min.css'?>">
		<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/style.css'?>">
		<!-- <link rel="stylesheet" href="<?php //echo (jf::url());?>/style/base.css" /> -->
		<link rel="shortcut icon" href="<?php echo (jf::url());?>/img/jlogo.png" />
	</head>
	<body>