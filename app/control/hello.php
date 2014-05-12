<?php

class HelloController extends JControl
{

	private $hello;

	public function __construct()
	{
		parent::__construct();
		$this->hello = new Hello();
	}

	function Start()
	{
		if(isset($_GET['name']))
			$this->Name = $_GET['name'];

		$this->hello->test();

		//jf::SaveUserSetting('shivam', 'dixit');

		$rbac = jf::$RBAC;

		$perm_id = $rbac->Permissions->add('delete_post', 'Can delete forum post');
		$role_id = $rbac->Roles->add('forum_moderator', 'Can moderate forum');

		$rbac->Permissions->assign($role_id, $perm_id);

		$rbac->Users->assign($role_id, 1);

		//jf::$User->CreateUser("mavihs", "om1234");
		//echo jf::Login("mavihs", "om1234");
		//jf::SaveUserSetting('shivam', 'dixit');

		return $this->Present ();
	}

	function _New()
	{
		echo "Hi";
	}

}

?>