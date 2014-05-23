<!--navbar
============-->
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="#" class="navbar-brand" style="color:white"><b>Single User Mode</b></a>

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

<div class="container">
    <div class="row">
        <div class="col-lg-3">

            <!--Accordion
            ============-->
            <div class="panel-group" id="accordion">
                <?php $i = 0;?>

                <?php foreach($this->allCategoryLesson as $category => $lessons): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="accordion" href="#section<?php echo ++$i;?>">
                                    <?php echo $category;?>
                                </a>
                            </h4>
                        </div>

                        <?php foreach($lessons as $lesson):?>
                            <div class="panel-collapse collapse" id="section<?php echo $i; ?>">
                                <div class="panel-body">
                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="<?php if (isset($_GET['lesson']) && $_GET['lesson'] == $lesson[0])
                                                            echo "active"; ?>">
                                            <a href="?lesson=<?php echo $lesson[0];?>">
                                                <?php if ($lesson[2]):?>
                                                    <span class="glyphicon glyphicon-ok"></span>
                                                <?php endif; ?>
                                                <?php echo $lesson[1]; ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            </div><!--Accordion ends-->
        </div>

        <div class="col-lg-9">

            <!-- Main content
            ==================-->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="page-header">
                        <h3><?php if(isset($this->error)) echo $this->error; else echo $this->lessonTitle; ?></h3>
                    </div>
                    <div class="page-body">
                        <?php if(!isset($this->error)) echo $this->htmlContent;?>
                    </div>
                </div>
            </div><!--Main content ends-->
            <hr>


            <!-- Options
            ============-->
            <div class="row">
                <div class="col-lg-9 center"><!--To Place it in the center-->
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option1"> Hints
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option2"> Parameter Inspector
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option3"> Cookie Inspector
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option4"> Lesson Plan
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option5"> Show Code
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="options" id="option6"> Solution
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div><!--Row ends-->
</div><!--container ends-->