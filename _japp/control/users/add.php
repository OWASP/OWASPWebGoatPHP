<?php
class UsersAddController extends BaseControllerClass
{
   function Start()
   {
      $App=$this->App;
      $View=$this->View;
      # Put your logic here
        if (isset($_POST["Username"]))
        {
            $r=$App->Session->CreateUser($_POST['Username'],$_POST['Password']);
            echo "Create user result: ".($r?"success":"failure");
            if (!$r) echo " (Username already exists!)";
            echo "<hr/>";
        }
      

      $this->Present();
   }
}
?>
