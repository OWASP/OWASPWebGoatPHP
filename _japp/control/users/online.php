<?php
class UsersOnlineController extends BaseControllerClass 
{
    function Start()
    {
        $App=$this->App;
        $Users=new SystemUsersModel($App);
        $this->View->Sessions=$Users->AllSessions();
        $this->Present();
    }
}
?>