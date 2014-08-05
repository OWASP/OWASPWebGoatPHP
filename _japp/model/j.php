<?php

namespace jf;

class ImportException extends \Exception{}

/**
 * jf helper class
 * entry point to all jframework modules
 * @author abiusx
 * @version 4.0
 */
class jf
{
    /**
     * Holds the request used to init jf. mostly the same with request unless on embed mode
     * @var string
     */
    static $BaseRequest=null;
    /**
     * Holds the request that is being run
     * @var string
     */
    static $Request=null;

    /**
     * returns root url of jframework
     */
    static function url()
    {
        return HttpRequest::Root();
    }
    static private $root=null;
    /**
     * Returns jframeworks root folder on filesystem (the one containing app and _japp)
     * @return string
     */
    static function root()
    {
        if (self::$root===null)
            self::$root=dirname(dirname(__DIR__));
        return self::$root;
    }
    static function moduleFile($module)
    {
        if (strlen($module)>3 && substr($module,0,3)=="jf/")
            $module="_japp/".substr($module,3);
        else
            $module="app/".$module;

        $file=jf::root()."/".$module.".php";
        return $file;
    }
    /**
     *
     * Loads a module into the context
     * As a rule of thumb, import should be used on class files and function/constant definition files.
     * For files that run something in no context, use run
     * @param string $module
     * @param array $scopeVars
     * @throws ImportException
     */
    static function import($module,$scopeVars=null)
    {

        $file=jf::moduleFile($module);
        if (!file_exists($file))
            throw new ImportException("File not found : {$file}");
        if (is_array($scopeVars)) foreach ( $scopeVars as $ArgName => $ArgValue )
            ${$ArgName} = $ArgValue;
        require_once($file);

    }
    /**
     * Runs a module. The difference with import is that this one uses require instead of require_once
     * @param string $module
     * @param array $scopeVars
     */
    static function run($module,$scopeVars=null)
    {
        $file=jf::moduleFile($module);
        if (!file_exists($file))
            throw new ImportException("File not found : {$file}");
        if (is_array($scopeVars)) foreach ( $scopeVars as $ArgName => $ArgValue )
            ${$ArgName} = $ArgValue;
        require($file);

    }
    /**
     * Returns a database connection which is already established
     * If no index provided, first (default) connection will be returned
     * @param integer $Index
     * @return BaseDatabase
     */
    static function db($Index=null)
    {
        return \jf\DatabaseManager::Database($Index);
    }
    /**
     * FrontController instance. Usually you do not need this.
     * @var FrontController
     */
    public static $App;
    /**
     * User management object. Everything to manage basic users.
     * @var UserManager
     */
    public static $User;
    /**
     * Extended user management object. These users have activation, locking and etc.
     * @var ExtendedUserManager
     */
    public static $XUser;
    /**
     * Session Management object. Session handling is here.
     * @var SessionManager
     */
    public static $Session;
    /**
     * Service Management. This object handles all service calls and service consumptions allowed by jFramework.
     * @var ServiceCore
     */
    public static $Services;
    /**
     * Profiler, used for timing different actions. Holds general application times.
     * @var Profiler
     */
    public static $Profiler;
    /**
     * Settings. This object allows you to save options for current session, current user and even current application
     * and retrieve them when needed.
     * @var SettingManager
     */
    public static $Settings;
    /**
     * Log Management. Logs system events, Analyses logs and etc.
     * @var LogManager
     */
    public static $Log;
    /**
     * Security Interface
     *
     * @var SecurityManager
     */
    public static $Security;

    /**
     * Role Based Access Control manager
     *
     * @var RBAC
     */
    public static $RBAC;
    /**
     * ErrorHandler
     *
     * @var ErrorHandler
     */
    public static $ErrorHandler;


    /**
     * Holds the current running modes of the application as flags. A mixture of RunModes:: constants
     * Used to detect modes, e.g command line, development, embedded, etc.
     * @var RunModes
     */
    public static $RunMode;
    /**
     * convenient wrapper for php time() function which allows
     * time manipulation for testing purposes
     * always use this instead of the php one
     */
    static function time()
    {
        if (self::$time===null)
            return time();
        return self::$time;
    }

    static function rand($min=0,$max=null)
    {
        if ($max===null)
            $max=1<<31;
        return jf::$Security->Random()%($max-$min)+$min;
    }
    /**
     * Use this to move time from real time for testing purposes
     * @param integer $difference optional, do not provide to reset
     */
    static function _movetime($difference=null)
    {
        if ($difference==null)
            self::$time=null;
        else
            self::$time=time()+$difference;
    }
    private static $time=null;



    /**
     * Internationalization Interface
     * @var I18nPlugin
     */
    public static $i18n;


    #### Options Section ####
    /**
     * save general settings
     * @param string $Name
     * @param int $Value
     * @param int $Timeout
     * @return mixed
     */
    static function SaveGeneralSetting($Name, $Value, $Timeout = null)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"SaveGeneral"),$a);
    }
    /**
     * load general settings
     * @param string $Name
     * @return mixed
     */
    static function LoadGeneralSetting($Name)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"LoadGeneral"),$a);
    }
    /**
     * save user settings
     * @param string $Name
     * @param int $Value
     * @param int $UserID
     * @param int $Timeout
     * @return mixed
     */
    static function SaveUserSetting($Name, $Value,$UserID=null,$Timeout = null)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"SaveUser"),$a);
    }
    /**
     * delete general settings
     * @param string $Name
     * @return mixed
     */
    static function DeleteGeneralSetting($Name)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"DeleteGeneral"),$a);
    }
    /**
     * loads save option
     * @param string $Name
     * @param int $UserID
     * @return mixed
     */
    static function LoadUserSetting($Name,$UserID=null)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"LoadUser"),$a);
    }
    /**
     * save settings in session
     * @param string $Name
     * @param int $Value
     * @param int $Timeout
     * @return mixed
     */
    static function SaveSessionSetting($Name,$Value,$Timeout = null)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"SaveSession"),$a);
    }
    /**
     * delete user setting
     * @param string $Name
     * @param int $ID
     * @return mixed
     */
    static function DeleteUserSetting($Name,$ID=null)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"DeleteUser"),$a);
    }
    /**
     * load settings for session
     * @param string $Name
     * @return mixed
     */
    static function LoadSessionSetting($Name)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"LoadSession"),$a);
    }
    /**
     * delete settings in session
     * @param string $Name
     * @return mixed
     */
    static function DeleteSessionSetting($Name)
    {
        $a=func_get_args();
        return call_user_func_array(array(jf::$Settings,"DeleteSession"),$a);
    }

    ###### Log Section ######
    static function Log ($Subject, $LogData, $Severity = 0)
    {
        return jf::$Log->Log($Subject, $LogData, $Severity);
    }
    ###### DBAL Section ######
    /**
     * Runs a SQL query in the database and retrieves result (via DBAL)
     *
     * @param String $Query
     * @param int $Param1 optional (could be an array)
     * @return mixed
     */
    static function SQL ($Query, $Param1 = null)
    {
        $args=func_get_args();
        if (is_array($Param1))
        {
            $args=$Param1;
            array_unshift($args,$Query);
        }
        return call_user_func_array(array(self::db(), "SQL"), $args);
    }


    ##### User Session Section #####
    static function CurrentUser ()
    {
        return jf::$Session->CurrentUser();
    }
    static function Username ()
    {
        return jf::$User->Username();
    }
    static function Login ($Username, $Password,$Force=null)
    {
        return jf::$User->Login($Username, $Password,$Force);
    }
    static function Logout ()
    {
        jf::$User->Logout();
    }

    ##### RBAC ######
    /**
     * Checks for a permission on a user
     * @param string|integer $Permission title or path of permission, or ID (in integer not string) form of it
     * @param string|integer $User username or user_id (in integer form), or null for current user
     * @throws \jf\RBACPermissionNotFoundException
     * @throws \jf\RBACUserNotFoundException
     * @return boolean
     */
    static function Check($Permission,$User=null)
    {
        return jf::$RBAC->Check($Permission,$User);
    }
    /**
     * Enforce a permission on current user
     * @param string|integer $Permission sent to RBAC::Check
     */
    static function Enforce($Permission)
    {
        return jf::$RBAC->Enforce($Permission);
    }

    static public function __Initialize (jfBaseFrontController $App)
    {
        self::$App = &$App;
        self::$DB = &$App->DB;
        self::$Session = &$App->Session;
        self::$User = &$App->User;
        self::$Services = &$App->Services;
        self::$Tracker = &$App->Profiler;
        self::$Profiler = &$App->Profiler;
        self::$Settings = &$App->Settings ;
        self::$Log = &$App->Log;
        self::$RBAC = &$App->RBAC;
        self::$Security=&$App->Security;
    }
    static function TablePrefix()
    {
        return DatabaseManager::Configuration()->TablePrefix;
    }
}

class j extends jf
{

}
