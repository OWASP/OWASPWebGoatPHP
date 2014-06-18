<!--navbar
============-->
<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/dashboard.css'?>">

<div class="navbar navbar-inverse navbar-fixed-top">
    <a href="#" class="navbar-brand" style="color:white"><b>Workshop Mode</b></a>
    <div class="container">
        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo jf::url()?>">Home</a></li>
                <li><a href="<?php echo jf::url().'/about'?>">About</a></li>
                <li><a href="#">Documentation</a></li>
                <li><a href="#">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">Overview</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Analytics</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="#">Create User</a></li>
                <li><a href="#">Delete User</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="#">Lesson Settings</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>

            <h2 class="sub-header">Users</h2>
            <div>
                <p>Total Users: 0</p>
                <p></p>
            </div>
            <h2 class="sub-header">Challenges</h2>
            <div>
                <p>Total Challenges: 0</p>
            </div>
        </div>
    </div>
</div>