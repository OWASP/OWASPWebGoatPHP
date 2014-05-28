<?php
####################################################################################
# add any more configuration you need for your application here, examples provided #
####################################################################################

//Path to lessons directory
define('LESSON_PATH', dirname(__FILE__)."/../../challenges/");

//Add autoload rules
#\jf\Autoload::AddRuleArray(array("Classname"=>jf::root()."/app/model/filepath.php"));
DoctrinePlugin::Load();