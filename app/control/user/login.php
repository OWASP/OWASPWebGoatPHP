<?php

class UserLoginController extends JControl
{
    function Start()
    {
        // if(jf::CurrentUser())   //If user is already logged in
        // {
        //     if(isset($_GET["return"]))
        //         $Return = $_GET["return"];
        //     else
        //         $Return = "";

        //     $this->Redirect(SiteRoot."".$Return,true,1);
        // }

        if(isset($_COOKIE["jFramework_Login_Remember"]))    //If remember me cookie is set
        {
            $temp = $_COOKIE["jFramework_Login_Remember"];
            $Username = explode("\n", $temp);
            $Username = $Username[0];
            $Password = $Username[1];
            $this->Result = jf::Login($Username,$Password);
        }

        if(isset($_POST["Username"]))
        {
            $this->Result = jf::Login($_POST['Username'],$_POST['Password']);
        }

        if(isset($this->Result) && $this->Result) //Login Successful
        {
        	if(isset($_POST["remember-me"]))
            {
                setcookie("jFramework_Login_Remember", $_POST["Username"] . "\n" . $_POST["Password"], time()+60*60*24*7, "/", null, null);
            }
            else
            {
                setcookie("jFramework_Login_Remember", null);
            }

            if(isset($_GET["return"]))
                $Return = $_GET["return"];
            else
                $Return = "";

            $this->Redirect(SiteRoot."".$Return);
        }

        return $this->Present();
    }
}

?>