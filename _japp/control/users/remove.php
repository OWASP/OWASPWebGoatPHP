<?php
class UsersRemoveController extends BaseControllerClass
{
    function Start()
    {
        $App=$this->App;
        $View=$this->View;
        # Put your logic here
        $Users=new SystemUsersModel($App);
        $View->Count=$Users->UserCount();
        if (isset($_POST['sel'])) //edit user
        {
            foreach($_POST['sel'] as $v)
            {
                $App->Session->RemoveUser($App->Session->Username($v));
            }
            echo "A total number of ".count($_POST['sel'])." users where removed.<hr/><a href='?'>Back</a>";
        }
        else //user list
        {
            $limit=30;
            $offset=0;
            if (isset($_GET['limit']))
                $limit=$_GET['limit'];
            if (isset($_GET['offset']))
                $offset=$_GET['offset'];
            
            $View->Users=$Users->AllUsers($offset,$limit);
            $View->Offset=$offset;
            $View->Limit=$limit;
        }        
        $this->Present();
    }
}
?>
