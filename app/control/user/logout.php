<?php

class UserLogoutController extends JControl
{
    function Start()
    {
        if(jf::CurrentUser())   //If user is already logged in
        {
            jf::Logout();
            setcookie("jFramework_Login_Remember", null,null,"/");
        }

        if(isset($_GET["return"]))
            $Return = $_GET["return"];
        else
            $Return = "";

        $this->Redirect(SiteRoot."".$Return);

    }
}

?>