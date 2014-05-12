<?php

class ModeSingleController extends JControl
{

	function __construct()
	{
		parent::__construct();
	}

	function Start()
	{
		if(jf::CurrentUser())
		{
			return $this->Present();
		}
		else
		{
			$this->Redirect(jf::url().'/user/login');
		}

	}
}

?>