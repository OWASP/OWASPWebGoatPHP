<link rel="stylesheet" href="<?php echo jf::url().'/style/flipclock.css';?>">
<script src="<?php echo jf::url().'/script/flipclock.min.js'?>"></script>
<script src="<?php echo jf::url().'/script/contest.js'?>"></script>

<!--navbar
============-->
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="#" class="navbar-brand"><b>Contest Mode</b></a>

        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo jf::url()?>">Home</a></li>
                <li><a href="<?php echo jf::url().'/about'?>">About</a></li>
                <li><a href="#">Rules</a></li>
                <li><a href="#">Leaderboard</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <?php if (jf::Check("contest")): ?>
                    <li><a href="<?php echo CONTEST_ADMIN_URL;?>">Dashboard</a></li>
                <?php endif;?>
                <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="text-center v-center">
        <h1>Contest starts in &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h1><br>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 v-center">
            <div class="countdown-clock"></div>
        </div>
    </div>
</div>
