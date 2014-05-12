<?php
class JchatLeaveService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        
        
        $Result=$jChat->Leave($Channel);
    	if (!$Result)
    	{
    		$out[Err]="1";
    	    $out[Error]="You're not in channel! You can't exit!";
    	}
    	else
    	{
            $out[Result]="Success";
    	}
    	return $out;
    }
}
?>
