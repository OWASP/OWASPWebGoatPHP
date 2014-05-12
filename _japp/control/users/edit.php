<?php
class UsersEditController extends BaseControllerClass
{
    function Start()
    {
        $App=$this->App;
        $View=$this->View;
        # Put your logic here
        $Users=new SystemUsersModel($App);
        $View->Count=$Users->UserCount();
        if (isset($_POST['uid'])) //edit user
        {
            $UserID=$_POST['uid'];
            $Username=$_POST['Username'];
            $Password=$_POST['Password'];
            if ($Password=="") $Password=null;
            $Result=$App->Session->EditUser($App->Session->Username($UserID),$Username,$Password);
            if ($Result)
            {
                echo "Edit successful.";
            }
            elseif ($Result==false)
            {
                echo "The new username you specified already exists!";
            }
            elseif ($Result==null)
            {
                echo "The old username you specified does not exist!";
            }
            echo "<hr/><a href='?' >Back</a>";
        }
        elseif (isset($_GET['uid'])) //selected user
        {
            $View->User=$Users->User($_GET['uid']);
            $View->User=$View->User[0];
            
            
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
