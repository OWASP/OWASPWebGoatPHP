<?php
class XuserSignupController extends JControl
{
    function Start ()
    {
    	$this->Username=jf::$XUser->Username();
    	if (isset($_GET['validate']))
    	{
    		$token=$_GET['validate'];
    		$UserID=jf::LoadGeneralSetting("activation_{$token}");
    		if (!jf::$XUser->UserIDExists($UserID))
    		{
    			$ErrorString="Invalid validation token.";
    		}
    		else //valid token, activate
    		{
    			if (jf::$XUser->IsActive($UserID))
					$ErrorString="Your account is already activated";
    			else
    			{
    				jf::$XUser->Activate($UserID);
    				jf::DeleteGeneralSetting("activation_{$token}");
    				$this->Success="Your account is succesfully activated. You may now login.";
    			}
    		}
    	}
        if (isset($_POST["Username"]))
        {
        	$Username=$_POST['Username'];
        	$Password=$_POST['Password'];
        	$Email=$_POST['Email'];
        	if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$Email))
        		$ErrorString="Invalid email address.";
        	elseif (jf::$XUser->UserID($Username)) {
        		$ErrorString="User already exists.";
            }
        	elseif ($_POST['Confirm']!=$Password)
        		$ErrorString="Password retype does not match.";
        	if (!isset($ErrorString))
			{
				$UserID=jf::$XUser->CreateUser($Username, $Password,$Email);
				if ($this->ActivationMail($Email,$UserID,$Username))
	        		$this->Success=true;
				else
					$ErrorString="Could not send confirmation email.";
			}
        }
        if (isset($ErrorString))
			$this->Error=$ErrorString;
        return $this->Present();
    }

    function ActivationMail($Email,$UserID,$Username)
    {
    	$ActivationToken=jf::$Security->RandomToken();
    	jf::SaveGeneralSetting("activation_{$ActivationToken}", $UserID);
    	$MyEmail="admin@".HttpRequest::Host();
		$Content="Thank you for joininig ".constant("jf_Application_Title"). " {$Username},
				Please open the following link in order to activate your account:

				".SiteRoot."/sys/xuser/signup?validate={$ActivationToken}

				If you did not sign up on this site, just ignore this email.";
		return mail($Email,"Account Confirmation",$Content,"From: ".constant("jf_Application_Name")." <{$MyEmail}>");
    }
}
?>
