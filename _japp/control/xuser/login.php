<?php
class XuserLoginController extends JControl
{
    function Start ()
    {
    	$this->Username=jf::$XUser->Username();
    	$Logged=false;
    	if (isset($_COOKIE["jframework_rememberme"]))
        {
        	$rememberMeToken= $_COOKIE["jframework_rememberme"];
        	$userID=jf::LoadGeneralSetting("rememberme_".$rememberMeToken);
			if ($userID>0)
			{
				$Result=jf::$XUser->ForceLogin($userID);
				$Logged=true;
			}
        }
        if (isset($_POST["Username"]))
        {
        	$Username=$_POST['Username'];
        	$Password=$_POST['Password'];
			$loginResult=jf::$XUser->Login($Username, $Password);
			if ($loginResult==false)
			{
				$UserID=jf::$XUser->UserID($Username);
				$res=jf::$XUser->LastError;
				if ($res==\jf\ExtendedUserErrors::Inactive)
					$ErrorString="Your account is not activated.";
				elseif ($res==\jf\ExtendedUserErrors::InvalidCredentials or $res==\jf\ExtendedUserErrors::NotFound)
					$ErrorString="Invalid Credentials.";
				elseif ($res==\jf\ExtendedUserErrors::Locked)
					$ErrorString="Your account is locked. Try again in ".floor(jf::$XUser->LockTime($Username)/60)." minute(s).";
				elseif ($res==\jf\ExtendedUserErrors::PasswordExpired)
				{
        			$Link=("./reset?user={$UserID}");
					$ErrorString="Your password is expired. You should <a href='{$Link}'>change your password</a>.";
				}
				elseif ($res==\jf\ExtendedUserErrors::TemporaryValidPassword)
				{
        			$Link=("./reset?user={$UserID}&temp={$Password}");
					$ErrorString="This is a temporary password. You should <a href='{$Link}'>reset your password</a> now.";
				}
				$Logged=false;
				$this->Error=$ErrorString;
			}
			else //logged in successfully
			{
				$Logged=true;
				if (isset($_POST['Remember']))
				{
					$timeout=60*60*24*30;
					$rememberMeToken=jf::$Security->RandomToken();
					jf::SaveGeneralSetting("rememberme_".$rememberMeToken,jf::CurrentUser(),$timeout);
					setcookie('jframework_rememberme',$rememberMeToken,jf::time()+$timeout);
				}
			}
        }
        if ($Logged==true)
        {
        	if (isset($_GET['return']))
        		$this->Redirect($_GET['return']);
        	$this->Success=true;
        }

        return $this->Present();
    }
}
?>