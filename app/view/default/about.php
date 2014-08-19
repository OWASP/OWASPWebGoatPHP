<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="<?php echo jf::url()?>" class="navbar-brand">OWASP WebGoatPHP</a>

        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo jf::url()?>">Home</a></li>
                <li  class="active"><a href="#">About</a></li>
                <li><a href="#">Documentation</a></li>
                <li><a href="#">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2>About OWASP WebGoatPHP</h2>
            <p>
                WebGoatPHP is a deliberately insecure web application developed using PHP to teach web application
                security. It offers a set of challenges based on various vulnerabilities listed in OWASP.
                The goal is to create an interactive teaching environment for web application security by offering
                lessons in the form of challenges. In each challenge the user must exploit the vulnerability to
                demonstrate their understanding. The application is a realistic teaching environment and supports
                four different modes.
            </p>

            <h3>Different Operating Modes</h3>

            <!--Accordion
            ===============-->
            <div class="panel-group" id="accordion">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Single-User Mode
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            User can browse all the challenges that are available, can view hints and submit solutions.
                            All the submissions are evaluated by server side scripts. Each challenge is accompanied by
                            a verbose explanation of the vulnerability and it's solution. This mode is suitable for
                            individuals who want a hands-on experience with various security flaws.
                            <br><br>
                            <a href="<?php echo SINGLE_MODE_LESSON_URL; ?>" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                Workshop Mode
                            </a>
                        </h4>
                        </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            In this mode, WebGoatPHP has a centralized control system.
                            A lecturer is presented with an admin dashboard where he can manage accounts of all the
                            participants that will connect to the server in workshop mode. He can control various
                            options like challenge selection, allow hints, allow providing feedback by other users etc.
                            A lecturer can monitor progress of all the students. This mode provides an ideal
                            collaborative learning environment.
                            <br><br>
                            <a href="<?php echo WORKSHOP_MODE_LESSON_URL; ?>" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                Contest Mode
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                        <div class="panel-body">
                            This mode is ideal for hosting CTF style contests. Admin of the contest can
                            add/select challenges, assign points, control duration of the contests, open hints for
                            participants etc. A contestant is required to sign-up before taking part in a contest.
                            A real time leader-board is also maintained on the basis of score and time.
                            <br><br>
                            <a href="<?php echo CONTEST_MODE_HOME; ?>" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                Secure Coding Mode
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse">
                        <div class="panel-body">
                            In secure coding mode user has to patch the security vulnerabilities.
                            For each challenge, the source code is presented to the user and he has to modify it in
                            such a way that vulnerability no longer exists. The user is not required to change the
                            source code of the application, instead only the relevant part of the source code is shown
                            to the user in a client-side IDE.
                            <br><br>
                            <a href="<?php echo SINGLE_MODE_LESSON_URL; ?>" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

            </div><!--Accordion ends-->

            <h3>Developers</h3>
            <ol>
                <li>Abbas Naderi</li>
                <li>Shivam Dixit</li>
                <li>Johanna Curiel</li>
                <li>Your Name Here</li>
            </ol>

            <h3>Hosting</h3>
            <p>The project is still under development and not hosted yet. Come back soon for more updates !</p>
        </div>
    </div>
</div>
