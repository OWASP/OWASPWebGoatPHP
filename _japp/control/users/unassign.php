<?php
class UsersUnassignController extends BaseControllerClass
{
	function Start()
	{
		$View=$this->View;
        if (isset($_POST['a']))
		{
            foreach($_POST['a'] as $A)
            {
                $A=explode("_",$A);
                if ($A[0]=='0' && $A[1]=='0')
                    $View->Result="Can not unassign root.";
                else
                    $this->App->RBAC->User_UnassignRole($A[0],$A[1]);
            }
            $View->Result=count($_POST['a']);
		}
		$View->Assignments=$this->App->RBAC->User_AllAssignments(false,$_GET['sort'],$_GET['offset'],$_GET['limit']);
        $View->Count=$this->App->RBAC->User_AllAssignmentsCount();

		$this->Present();
	}
}
?>