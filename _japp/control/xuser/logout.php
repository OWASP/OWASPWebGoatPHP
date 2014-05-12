<?php
class XuserLogoutController extends JControl 
{
    function Start()
    {
		$this->Username=jf::$XUser->Username();
    	
        jf::$XUser->Logout(jf::CurrentUser());
        setcookie("jframework_rememberme", null,null);
        if (isset($_GET["return"]))
            $this->Return=$_GET["return"];
        else
            $this->Return="./login";
        
        return $this->Present();
    }
}
?>