<?php
class UserLoginController extends BaseControllerClass
{
    function Start ()
    {
        $this->UserID = j::$Session->UserID;
        $this->Username = j::$Session->Username();
        $x=new XuserPlugin();
        if (isset($_COOKIE["jFramework_Login_Remember"]))
        {
            $temp = $_COOKIE["jFramework_Login_Remember"];
            $Username = explode("\n", $temp);
            $Password = $Username[1];
            $Username = $Username[0];
            $this->Result = $x->Login($Username,$Password,$Error); //$App->Session->Login($Username, $Password);
            $this->Username = $Username;
            $this->Error=$Error;
        }
        if (isset($_POST["Username"]))
        {
            $this->Result = $x->Login($_POST['Username'],$_POST['Password'],$Error);//$App->Session->Login($_POST["Username"], $_POST["Password"]);
            $this->Error=$Error;
        }
        if ($this->Result) //Login Successful
        {
        	if ($_POST["Remember"])
            {
                setcookie("jFramework_Login_Remember", $_POST["Username"] . "\n" . $_POST["Password"], time()+60*60*24*7, "/", null, null);
            }
            else
            {
                setcookie("jFramework_Login_Remember", null);
            }
            if (isset($_GET["return"]))
                $Return = $_GET["return"];
            else
                $Return = "";
            $this->Redirect(SiteRoot."".$Return,true,1);
        }
        $this->Present();
    }
}
?>