<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="#" class="navbar-brand">OWASP WebGoatPHP</a>

        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="<?php echo jf::url().'/about' ?>">About</a></li>
                <li><a href="#">Documentation</a></li>
                <li><a href="#">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <?php if (jf::CurrentUser()):?>
                    <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
                <?php endif;?>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="jumbotron">
            <h1>WebGoatPHP</h1>
            <p>
                WebGoatPHP is a deliberately insecure web application developed using PHP to teach
                web application security. It offers a set of challenges based on various vulnerabilities listed
                in OWASP. In each challenge the user must exploit the real vulnerability to demonstrate their
                understanding. The application is a realistic teaching environment
                and supports four different modes.
            </p>
            <a href="<?php echo jf::url().'/about' ?>" class="btn btn-primary btn-lg">Learn more &raquo;</a>
    </div>
    <hr>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3>Single-User Mode</h3>
            <p>Browse all the lessons that are available. You can view hints and submit solutions.
                This mode is suitable for individuals who want a hands-on experience with various security flaws.</p>
            <a href="<?php echo SINGLE_MODE_LESSON_URL ?>" class="btn btn-default">Get Started</a>
        </div>
        <div class="col-md-3">
            <h3>Workshop Mode</h3>
            <p>It has a  centralized control system using which a lecturer controls various options like
                challenge selection, hints etc. This mode provides an ideal collaborative learning environment.</p>
            <a href="<?php echo WORKSHOP_MODE_LESSON_URL ?>" class="btn btn-default">Get Started</a>
        </div>
        <div class="col-md-3">
            <h3>Contest (CTF) Mode</h3>
            <p >Take part in a live CTF contest. You are required to sign-up before you can take part in a contest.</p>
            <a href="#" class="btn btn-default">Get Started</a>
        </div>
        <div class="col-md-3">
            <h3>Secure Coding Mode</h3>
            <p >Patch security vulnerabilities and learn about secure coding practices.
                You are required to modify vulnerable source code in such a way that vulnerability no longer exists.</p>
            <a href="#" class="btn btn-default">Get Started</a>
        </div>
    </div>
</div>
