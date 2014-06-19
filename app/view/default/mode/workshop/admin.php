<script src="<?php echo jf::url()."/script/workshop.js"?>"></script>
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
                <li class="active"><a href="#overview">Overview</a></li>
                <li><a href="#reports">Reports</a></li>
                <li><a href="#analytics">Analytics</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="#create">Create User</a></li>
                <li><a href="#delete">Delete User</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="#settings">Lesson Settings</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>

            <h2 id="sub-heading" class="col-sm-offset-2">Create User</h2>
            <br>
            <div class="main-content">
                <div id="ajax-message"></div>
                <form class="form-horizontal" role="form" method="POST" id="create-user-form">
                    <div class="form-group">
                        <label for="username" class="col-sm-2 control-label">User Name:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="username" id="username" placeholder="User Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password:</label>
                        <div class="col-sm-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-3">
                            <input type="submit" name="submit" value="Login" class="btn btn-default">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>