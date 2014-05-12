<?php
class UserLogoutController extends BaseControllerClass 
{
    function Start()
    {
        $App=$this->App;
        $View=$this->View;
        if (!$App->Session->UserID) 
            $View->Username=null;
        else
            $View->Username=$App->Session->Username();

        $App->Session->Logout();
        
        setcookie("jFramework_Login_Remember", null,null,"/");
        if (isset($_GET["return"]))
            $View->Return=$_GET["return"];
        else
            $View->Return="/user/login";
        $View->Return=SiteRoot.$View->Return;
        $this->Present();
    }
}
?>