<link rel="stylesheet" href="<?php echo jf::url().'/style/flipclock.css';?>">
<script src="<?php echo jf::url().'/script/flipclock.min.js'?>"></script>
<script src="<?php echo jf::url().'/script/contest.js'?>"></script>

<?php if(isset($this->TimeRemaining)):?>
    <script>
        var time = <?php echo $this->TimeRemaining;?>;
        $(document).ready(function(){
            var countdownTimer = $('.countdown-clock').FlipClock(time, {
                countdown: true,
                clockFace: 'DailyCounter',
                callbacks: {
                    stop: function() {
                        // Refresh the page
                        window.location.href = window.location.href;
                    }
                }
            });
        });
    </script>
<?php endif; ?>

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
    <?php if(isset($this->TimeRemaining)):?>
        <div class="text-center">
            <h1 class="text-success">Contest <strong><?php echo $this->ContestName;?></strong> starts in</h1>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-7 col-sm-offset-3 v-center">
                <div class="countdown-clock"></div>
            </div>
        </div>
    <?php endif;?>

    <?php if(isset($this->Error)):?>
        <div class="alert alert-danger text-center"><?php echo $this->Error; ?></div>
    <?php endif;?>
</div>
