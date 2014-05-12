<?php
/**
 * This is the pre-hook. It is run before the request is run, after everything is loaded.
 */
jf::run("config/transition");

$Stats=new StatsPlugin();
$Stats->Insert();
