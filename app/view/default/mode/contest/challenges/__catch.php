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
                <li><a href="<?php echo CONTEST_MODE_HOME;?>">Challenges</a></li>
                <li><a href="<?php echo GITHUB_URL;?>" target="_blank">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>


<div class="container">
    <h1 class="text-center text-warning"><?php echo $this->ChallengeName;?></h1>
    <?php
    if (isset($this->Submission)) {
        if ($this->Submission) {
            echo "<div class='alert alert-success text-center'>Congratulations! You successfully completed this challenge</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Oops! Try again :(</div>";
        }
    }
    ?>
    <div class="jumbotron">
        <?php echo $this->Content;?>
    </div>
</div>
