<?php
####################################################################################
# add any more configuration you need for your application here, examples provided #
####################################################################################

//Path to lessons directory
define('LESSON_PATH', dirname(__FILE__)."/../../challenges/single/");
define('CONTEST_CHALLENGE_PATH', dirname(__FILE__)."/../../challenges/contest/");

//URL of lessons
define('SINGLE_MODE_LESSON_URL', jf::url()."/mode/single/challenges/");
define('WORKSHOP_MODE_LESSON_URL', jf::url()."/mode/workshop/challenges/");
define('WORKSHOP_ADMIN_URL', jf::url()."/mode/workshop/admin");
define('CONTEST_MODE_DIR', jf::url()."/mode/contest/"); // Notice the trailing slash
define('CONTEST_MODE_HOME', CONTEST_MODE_DIR."home");
define('CONTEST_ADMIN_URL', CONTEST_MODE_DIR."admin");
define('CONTEST_MODE_LESSON_URL', CONTEST_MODE_DIR."challenges/");

// GitHub URL (OWASP)
define('GITHUB_URL', 'https://github.com/OWASP/OWASPWebGoatPHP/');

//Add autoload rules
#\jf\Autoload::AddRuleArray(array("Classname"=>jf::root()."/app/model/filepath.php"));
DoctrinePlugin::Load();
