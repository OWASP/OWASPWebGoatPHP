<?php
class JchatWhoamiService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        $LastID=null;
        $Result=$jChat->WhoAmI($Channel,$LastID);
        if ($Result)
    	{
            $out[Result]="Success";
            $out[User]=$Result;
            $out[LastID]=$LastID;
    	}
    	else 
    	{
    	    $out[Err]="1";
    	    $out[Error]="You are not in channel!";
    	}
    	return $out;
    }
}
?>
