<?php
class UserCaptchaController extends BaseControllerClass
{
	function Start()
	{
		usleep(500*1000);
		$c=new CaptchaPlugin();
		$c->Present($_GET['title']);
	}
}