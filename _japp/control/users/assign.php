<?php
class UsersAssignController extends BaseControllerClass
{
	function Start()
	{
		$View=$this->View;
        
		if (isset($_POST['rid']))
		{
		    $Replace=$_POST['Replace'];
            if ($_POST['rid'])
		    foreach ($_POST['rid'] as $R)
            {
                if ($_POST['uid'])
                foreach ($_POST['uid'] as $U)
                {
                    $this->App->RBAC->User_AssignRole($R,$U,$Replace);
                }
            }
            $View->Result=count($_POST['rid'])*count($_POST['uid']);
		    
		}
		
		$Userman=new SystemUsersModel($this->App);
		$View->Users=$Userman->AllUsers();
		$View->Roles=$this->App->RBAC->Role_All();
		
		$this->Present();
	}
}
?>