<?php
class JchatSendService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        
        $Message=$Params[Message];
        if ($Message=="")
            return array("Error"=>"Don't send empty messages","Err"=>"2");
        $Message=htmlspecialchars($Message);
        $Result=$jChat->Send($Message,$Channel);
        if (!$Result)
    	{
    		$out[Err]="1";
    	    $out[Error]="You should login to be able to send!";
    	}
    	else
    	{
            $out[Result]=$Result;
    	}
    	return $out;
    }
}
?>
