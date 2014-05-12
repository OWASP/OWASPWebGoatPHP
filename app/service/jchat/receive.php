<?php
class JchatReceiveService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        
        $LastID=$Params[LastID];
        if (!is_numeric($LastID))
            return array('Error'=>"Invalid LastID","Err"=>"2");
        
        
        
        $Result=$jChat->Receive($Channel,$LastID);
    	if (!$Result)
    	{
    		$out[Err]="1";
    	    $out[Error]="No new messages!";
    	}
    	else
    	{
            $out=$Result;
    	}
    	return $out;
    }
}
?>
