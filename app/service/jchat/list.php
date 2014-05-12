<?php
class JchatListService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        
        $Result=$jChat->ChannelUsers($Channel);
    	if (!$Result)
    	{
    		$out[Err]="1";
    	    $out[Error]="No one is in the channel!";
    	}
    	else
    	{
            $out=$Result;
    	}
    	return $out;
    }
}
?>
