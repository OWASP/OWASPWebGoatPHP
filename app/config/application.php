<?php
jf::import("config/setup"); //setup on first run INITIALSETUP
#####################################################################
### this is the application configuration file, please configure  ###
### and customize your application before starting to use it      ###
#####################################################################

/**
 * Identity
 *
 * Define your jframework powered web application here. Set at least a version, a Name and a
 * title for your application. Name would better follow identifier rules.
 */
const jf_Application_Version="1.0"; //put version of your application here, as a string.
const jf_Application_Name="WebGoatPHP" ; //follow identifier rules for this name
const jf_Application_Title="OWASP WebGoatPHP" ; //title of your application


/**
 * Mode detection
 * here jframework tries to determine what mode its running at,
 * Deploy, Develop or Command Line. Provide necessary logic for it to determine correctly
 */
if (strpos(HttpRequest::Host(), "LOCALHOSTURL") !== false) {
    jf::$RunMode->Add(RunModes::Develop);
} elseif (php_sapi_name()=="cli") {
    jf::$RunMode->Add(RunModes::CLI);
} else {
    jf::$RunMode->Add(RunModes::Deploy);
}

/**
 * Siteroot
 *
 * jframework requires to know where your site root is, e.g http://jframework.info
 * or http://tld.com/myfolder/myjf/deploy
 * automatically determines this, so change it and define it manually only when necessary
 * you can use this constant in your views for absolute urls
 */
define("SiteRoot", HttpRequest::Root());

/**
 * Database Setup
 *
 * jframework requires at least a database for its core functionality.
 * You can also use "no database-setup" if you do not need jframework libraries and want a semi-static
 * web application, in that case, comment or remove the database username definition
 */
\jf\DatabaseManager::AddConnection(
    new \jf\DatabaseSetting("mysqli", "DBNAME", "DBUSER", "DBPASS")
);

/**
 * Error Handling
 *
 * jframework has an advanced error handler built-in.
 * Errors should not be presented to the end user on a release environment,
 * this is automatically handled by presentErrors, which toggles between logging
 * and displaying.
 */
if (jf::$RunMode->IsCLI() or jf::$RunMode->IsEmbed()) {
    jf\ErrorHandler::$Enabled=false; //disable it if embedded or CLI because another system is handling it.
} else {
    jf\ErrorHandler::$Enabled=true; //Enables jframework's built-in error handler
}

jf::$ErrorHandler->SetErrorHandler();

if (jf::$RunMode->IsDevelop()) {
    jf\ErrorHandler::$PresentErrors=true;
} else {
    jf\ErrorHandler::$PresentErrors=false;
}

/**
 * Bandwidth Management
 *
 * jframework handles all file feeds and downloads manually.
 * Its FileManager has the ability to limit download speed of files larger than a specific size.
 * Set both the initial size and the limit here.
 */
jf\DownloadManager::$BandwidthLimitInitialSize=-1;  # negative number disables it
jf\DownloadManager::$BandwidthLimitSpeed=1024*1024;

/**
 * Iterative Templates
 *
 * If this is set, jframework viewer would look into the view folder and all its ancestor folders
 * to find a template folder, and would display the first template it finds.
 * Otherwise only the same folder is checked for templates.
 */
jf\View::$IterativeTemplates=true;

jf::import("config/more");
