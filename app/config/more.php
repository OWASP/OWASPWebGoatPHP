<?php
####################################################################################
# add any more configuration you need for your application here, examples provided #
####################################################################################

//Path to lessons directory
define('LESSON_PATH', dirname(__FILE__)."/../../challenges/");

//URL of lessons
define('SINGLE_MODE_LESSON_URL', jf::url()."/mode/single/challenges/");
define('WORKSHOP_MODE_LESSON_URL', jf::url()."/mode/workshop/challenges/");
define('WORKSHOP_ADMIN_URL', jf::url()."/mode/workshop/admin");
define('CODING_MODE_LESSON_URL', jf::url()."/mode/coding/challenges/");
define('CONTEST_MODE_DIR', jf::url()."/mode/contest/"); // Notice the trailing slash
define('CONTEST_MODE_HOME', CONTEST_MODE_DIR."home");

//Add autoload rules
#\jf\Autoload::AddRuleArray(array("Classname"=>jf::root()."/app/model/filepath.php"));
DoctrinePlugin::Load();