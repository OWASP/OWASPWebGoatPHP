<script type="text/javascript">
    var hints = <?php if(isset($this->hints)) echo json_encode($this->hints); else echo "['No hints']"?>;
</script>
<!--navbar
============-->
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="#" class="navbar-brand"><b>Workshop Mode</b></a>

        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a href="?refresh=true" class="btn navbar-btn btn-xs btn-success pull-left">Refresh List</a>

        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo jf::url()?>">Home</a></li>
                <li><a href="<?php echo jf::url().'/about'?>">About</a></li>
                <li><a href="<?php echo GITHUB_URL;?>" target="_blank">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <?php if (jf::Check("workshop")): ?>
                    <li><a href="<?php echo WORKSHOP_ADMIN_URL;?>">Dashboard</a></li>
                <?php endif;?>
                <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">

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

                        <div class="panel-collapse collapse" id="section<?php echo $i; ?>">
                            <div class="panel-body">
                                <?php foreach($lessons as $lesson):?>
                                    <?php if (isset($this->hiddenLessons)
                                        && in_array($lesson[0], $this->hiddenLessons)) continue;?>
                                        <ul class="nav nav-pills nav-stacked">
                                            <li class="<?php if ((isset($this->nameOfLesson) &&
                                                $this->nameOfLesson == $lesson[0])) echo "active"; ?>">
                                                <a href="<?php echo WORKSHOP_MODE_LESSON_URL."$lesson[0]/"?>">
                                                    <?php if ($lesson[1]->isCompleted()):?>
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    <?php endif; ?>
                                                    <?php echo $lesson[1]->getTitle(); ?>
                                                </a>
                                            </li>
                                        </ul>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div><!--Accordion ends-->
        </div>

        <div class="col-md-9">

            <!-- Main content
            ==================-->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h3><?php if(isset($this->error)) echo $this->error; else echo $this->lessonTitle; ?></h3>
                            </div>
                            <?php if (!isset($this->error)):?>
                                <div class="col-md-2">
                                    <a href="<?php echo WORKSHOP_MODE_LESSON_URL."$this->nameOfLesson/reset/"; ?>" class="btn btn-sm btn-danger navbar-btn" id="reset-btn">Reset Lesson</a>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="page-body">
                        <?php if(!isset($this->error)) echo $this->htmlContent;?>
                    </div>
                </div>
            </div><!--Main content ends-->
            <hr>

            <div id="options-container" class="text-success">

            </div>
            <div class="hidden" id="complete-scode">
                <pre class="prettyprint"><?php if (isset($this->completeSourceCode)) echo $this->completeSourceCode; ?></pre>
            </div>
            <br>

            <!-- Options
            ============-->
            <div class="row">
                <div class="col-md-8 col-md-offset-2"><!--To Place it in the center-->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default" id="hints-btn" >Hints</button>
                        <button type="button" class="btn btn-default" id="parameter-btn">Parameters</button>
                        <button type="button" class="btn btn-default" id="cookie-btn">Cookies</button>
                        <button type="button" class="btn btn-default" id="lesson-plan-btn">Lesson Plan</button>
                        <button class="btn btn-default" id="show-php-btn">Show PHP</button>
                        <button type="button" class="btn btn-default" id="solution-btn">Solution</button>
                    </div>
                </div>
            </div>

        </div>
    </div><!--Row ends-->
</div><!--container ends-->
