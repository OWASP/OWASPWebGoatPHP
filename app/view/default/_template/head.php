<?php if (jf::$RunMode->IsEmbed()) return; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->Title()?:jf_Application_Title; ?></title>
<link rel="shortcut icon" href="<?php echo (jf::url());?>/img/jlogo.png" />
<link rel="stylesheet" href="<?php echo (jf::url());?>/style/base.css" />
<script src="<?php echo jf::url();?>/script/jquery-1.9.1.min.js"></script>
<?php echo $this->HeadData();?>
<base href="<?php echo jf::url();?>/" />
</head>
<body>
