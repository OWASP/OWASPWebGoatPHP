<script src="<?php echo jf::url()."/script/contest-admin.js"?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/dashboard.css'?>">
<!--navbar
============-->
<div class="navbar navbar-inverse navbar-fixed-top">
    <a href="#" class="navbar-brand">Contest Mode</a>
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
        <div class="col-sm-3 col-md-2 sidebar" id="side-nav">
            <ul class="nav nav-sidebar">
                <li id="overview"><a href="#overview">Overview</a></li>
                <li id="reports"><a href="#reports">Reports</a></li>
                <li id="analytics"><a href="#analytics">Analytics</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li id="users"><a href="#users">Users</a></li>
                <li id="challenges"><a href="#challenges">Challenges</a></li>
            </ul>
            <ul class="nav nav-sidebar">
            </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header" id="heading"></h1>
            <div id="main-content">
            </div><!--End main-content-->

        </div>
    </div> <!--Row ends-->
</div>
