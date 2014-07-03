<?php
namespace jf;
/**
 * jframework session manager
 * This class Handles user session and user management (credentials).
 * @author abiusx
 * @version 4.1
 */
class SessionManager extends Model
{
    private static $SessionName="JFSESSIONID";


    function __construct()
    {
        session_name ( self::$SessionName );
        if (!session_start ())
            throw new Exception("Unable to session_start().");
        $this->Refresh();

    }
    /**
     * Would close the session information. Used for comet and similar paraller requests
     */
    function Finish()
    {
        session_write_close (); //This would release the session_start lock and prevent request sequentialization
    }
    /**
    If a user is logged in, This variable holds his UserID, else it would be null.
     */
    protected $UserID=null;

    /**
     * Refresh the session. If it exists, update its timing, otherwise create it. Also removes expired sessions.
     * @return boolean true on new session, false on existing
     */
    function Refresh()
    {
        $SessionID = $this->SessionID();
        $Result = jf::SQL ( "SELECT * FROM {$this->TablePrefix()}session WHERE SessionID=?", $SessionID );

        if (! $Result)
        {
            $this->CreateSession();
            $this->_Sweep ();
            return true;
        }
        if (count ( $Result ) == 1)
        {
            $Result = $Result [0];
            $this->SetCurrentUser($Result['UserID']);
            $LoginDate = $Result ['LoginDate'];
            $LastAccess = $Result ['LastAccess'];
            $LoginTimestamp = $LoginDate; //strtotime($LoginDate);
            $LastAccessTimestamp = $LastAccess; //strtotime($LastAccess);
            $NowTimestamp = jf::time ();
            $Dis = $NowTimestamp - $LastAccessTimestamp;
            $LoginTime = $NowTimestamp - $LoginTimestamp;
            if ($Dis > self::$NoAccessTimeout or $LoginTime > self::$ForcedTimeout)
            {
                $this->ExpireSession();
            }
            else
                jf::SQL ( "UPDATE {$this->TablePrefix()}session SET LastAccess=? ,AccessCount=AccessCount+1 , CurrentRequest=? WHERE SessionID=?", jf::time (), jf::$Request,session_id () );

            $this->_Sweep ();
            return false;
        }
        else
            throw new \Exception("More than one session record found in database!");
    }

    public static $SweepRatio=.1;
    public static $NoAccessTimeout=7200; // 2 hours
    public static $ForcedTimeout=604800; //a day
    /**
     * Sets the current user whose session is active.
     * The current user is referenced by functions requiring an active user
     * @param integer $UserID
     */
    function SetCurrentUser($UserID)
    {
        $this->UserID = $UserID;
        if ($this->UserID == 0) $this->UserID = null;
    }
    /**
    Removes outdated session info from session table
     */
    function _Sweep($force=false)
    {
        //Removes timed out session
        if (!$force) if (rand ( 0, 1000 ) / 1000.0 > self::$SweepRatio) return; //10%
        $Now = jf::time ();
        jf::SQL ( "DELETE FROM {$this->TablePrefix()}session WHERE LastAccess<? OR LoginDate<?", $Now- self::$NoAccessTimeout, $Now- self::$ForcedTimeout);
    }



    /**
    Creates a new session for current visitor.
     */
    function CreateSession()
    {
        return jf::SQL ( "INSERT INTO {$this->TablePrefix()}session (UserID,SessionID,LoginDate,LastAccess,IP,CurrentRequest) VALUES (?,?,?,?,?,?)",
                                             0, $this->SessionID (), jf::time (), jf::time (), HttpRequest::IP(),"" );
    }

    /**
     *
     * Called when a session expires
     * Destroys the session, and recreates it
     */
    function ExpireSession()
    {
        $this->DestroySession();
        $this->CreateSession();

    }

    /**
     *
     * changes sessionID both in cookie and database, without destroying the session
     * @return boolean
     */
    function RollSession()
    {
        $oldSession=$this->SessionID();
        session_regenerate_id();
        $newSession=$this->SessionID();
        $r=jf::SQL("UPDATE {$this->TablePrefix()}session SET SessionID=? WHERE SessionID=?",$newSession,$oldSession);
        $this->_Sweep();
        return ($r>=1);
    }
    /**
     * Destroys current session, removing all session variables and parameters
     */
    function DestroySession()
    {
        jf::SQL("DELETE FROM {$this->TablePrefix()}session WHERE SessionID=?",$this->SessionID());
        if (isset ( $_COOKIE [session_name ()] ))
        {
            setcookie ( session_name (), '', jf::time () - 42000, '/' );
        }
        $this->SetCurrentUser(null);
        $_SESSION=array();
        session_regenerate_id ( true );
    }
    /**
     * Accessor for session_id in case non-native method required
     * @param string $set optional new session ID to set
     */
    function SessionID($NewID=null)
    {
        if ($NewID)
            session_id($NewID);
        return session_id();
    }
    /**
     * Returns the number of online visitors based on established session.
     *
     * @return Integer Number of online visitors
     */
    function OnlineVisitors()
    {
        $Result = jf::SQL( "SELECT COUNT(*) AS Result FROM {$this->TablePrefix()}session" );
        return $Result [0] ["Result"];
    }

    /**
     * returns current UserID, null on not user logged in
     * @return Integer or null
     */
    function CurrentUser()
    {
        return $this->UserID;
    }

    /**
     * Checks whether or not a session is logged in
     * @param string $SessionID
     * @return boolean
     */
    function IsLoggedIn($SessionID=null)
    {
        if ($SessionID===null)
            $SessionID=$this->SessionID();
        $Result=jf::SQL("SELECT COUNT(*) AS Result FROM {$this->TablePrefix()}session WHERE SessionID=? AND UserID!=0",$SessionID);
        return $Result[0]['Result']>=1;
    }
}

?>