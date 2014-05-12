<?php
class XuserResetController extends JControl
{
    function Start ()
    {
    	$this->Username=jf::$XUser->Username();
    	if (isset($_GET['user']))
    	{
    		$UserID=$_GET['user'];
    		if (isset($_GET['temp']))
    			$TemporaryPass=$_GET['temp'];
    		else
    			$TemporaryPass="";
    		if (!jf::$XUser->UserIDExists($UserID))
    			$ErrorString="Invalid password recovery information.";
    		else
    		{
	    		$this->Username=jf::$XUser->Username($UserID);
    			$this->TempPass=$TemporaryPass;
    		}
    	}
    	if (isset($_POST['TempPass']))
    	{
    		$TempPass=$_POST['TempPass'];
    		$Username=$_POST['Username'];
    		$Password=$_POST['Password'];
    		if ($Password!=$_POST['Retype'])
    			$ErrorString='Retype does not match password';
    		elseif (!jf::$XUser->UserExists($Username))
    			$ErrorString="Invalid username.";
    		$res=jf::$XUser->Login($Username, $TempPass);
    		if (!$res && jf::$XUser->LastError==\jf\ExtendedUserErrors::Inactive)
    			$ErrorString="Your account is not yet activated.";
    		elseif (!$res && jf::$XUser->LastError==\jf\ExtendedUserErrors::TemporaryValidPassword)
			{
				if (!jf::$XUser->EditUser($Username, $Username,$Password))
					$ErrorString="Unable to reset password";
				else
				{
					jf::$XUser->Reset(jf::$XUser->UserID($Username));
					$this->Success="New password successfully set. You can now <a href='./login'>login</a>.";
				}				
			}
			elseif (!$res && jf::$XUser->LastError==\jf\ExtendedUserErrors::PasswordExpired)
			{
				if (!jf::$XUser->EditUser($Username, $Username,$Password))
					$ErrorString="Unable to change password";
				else
				{
					$this->Success="New password successfully set. You can now <a href='./login'>login</a>.";
				}				
				
			}
    		else 
    			$ErrorString="Invalid current password.";
    	}
        if (isset($_POST["Email"]))
        {
        	$Email=$_POST['Email'];
        	if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$Email))
        		$ErrorString="Invalid email address.";
        	elseif (!jf::$XUser->FindByEmail($Email))
        		$ErrorString="Email address not found.";

        	if (!isset($ErrorString))
			{
				$UserID=jf::$XUser->FindByEmail($Email);
				if ($this->ResetMail($Email,$UserID))
	        		$this->Success=true;
				else
					$ErrorString="Could not send recovery email.";		
			}
        }
        if (isset($ErrorString))
			$this->Error=$ErrorString;	
        return $this->Present();
    }
    
    function ResetMail($Email,$UserID)
    {
		$Temp=jf::$XUser->TemporaryResetPassword($UserID);
    	$MyEmail="admin@".HttpRequest::Host();
    	$UserInfo=jf::$XUser->UserInfo($UserID);
		$Content="You have requested to reset the password for username '{$UserInfo['Username']}',
				Please open the following link in order to reset your password:
				
				".SiteRoot."/sys/xuser/reset?user={$UserID}&temp={$Temp}
		
				The above link is valid for one hour only.
				If you did not ask your password to be reset, just ignore this email.\n";
// 		echo $Content;    	
		return mail($Email,"Account Password Recovery",$Content,"From: ".constant("jf_Application_Name")." <{$MyEmail}>");
    }
}
?>