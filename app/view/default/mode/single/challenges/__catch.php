<script type="text/javascript">
    var hints = <?php if(isset($this->hints)) echo json_encode($this->hints); else echo "['No hints']"?>;
</script>
<!--navbar
============-->
<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <a href="#" class="navbar-brand"><b>Single User Mode</b></a>

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
                <li><a href="#">Documentation</a></li>
                <li><a href="<?php echo GITHUB_URL;?>" target="_blank">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
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
                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="<?php if ((isset($this->nameOfLesson) &&
                                            $this->nameOfLesson == $lesson[0])) echo "active"; ?>">
                                            <a href="<?php echo SINGLE_MODE_LESSON_URL."$lesson[0]/"?>">
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
                            <div class="col-md-9">
                                <h3><?php if(isset($this->error)) echo $this->error; else echo $this->lessonTitle; ?>
                                </h3>
                            </div>
                            <?php if (!isset($this->error)):?>
                                <div class="col-md-1"><!--Placed outside if so that in false condition
                                also reset btn is aligned properly-->
                                    <?php if (isset($this->sourceCode)): ?>
                                        <a href="#" class="btn btn-sm btn-success navbar-btn" id="fix-btn">Fix It!</a>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-2">
                                    <a href="<?php echo SINGLE_MODE_LESSON_URL."$this->nameOfLesson/reset/"; ?>"
                                       class="btn btn-sm btn-danger navbar-btn" id="reset-btn">Reset Lesson</a>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="page-body">
                        <?php if(!isset($this->error)) echo $this->htmlContent;?>

                        <div id="source-code" class="hidden">
                            <br>
                            <h4 class="text-center">Source Code</h4>
                            <div id="editor" style="height: 260px;"><?php if(isset($this->sourceCode)) echo $this->sourceCode;?></div>
                            <form id="scode-form" method="POST">
                                <!--To submit source code-->
                                <input type="hidden" name="sourceCode" id="scode-inp">
                            </form>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-sm-offset-5">
                                    <a href="#" class="btn btn-success" id="scode-submit-btn">Submit</a>
                                    <a href="#" class="btn btn-default" id="scode-reset-btn">Reset</a>
                                </div>
                            </div>
                        </div>
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

<script src="<?php echo jf::url();?>/script/ace/src-min-noconflict/ace.js" type="text/javascript"></script>
<script src="<?php echo jf::url();?>/script/ace/src-min-noconflict/mode-php.js" type="text/javascript"></script>
<script>
    var editor = ace.edit("editor");
    var initialCode = editor.getSession().getValue();
    editor.setTheme("ace/theme/solarized_light");
    editor.getSession().setMode({path:"ace/mode/php", inline:true});    // Won't work without inline. REF #1142 ace repo
</script>
