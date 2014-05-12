<?php
class JchatJoinService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("plugin.jchat.main");
        $jChat=new jChat($this->App);
        
        $Channel=$Params[Channel];
        if (!is_numeric($Channel)) $Channel=0;
        
        $Nickname=$Params[Nickname];
        if (strlen($Nickname)<1)
            return array("Error"=>"Nickname must be at least 3 characters",
                        "Err"=>"3");
        $Nickname=htmlspecialchars($Nickname);
        
        $Result=$jChat->Join($Nickname,$Channel);
        if (!$Result)
    	{
            $out[Result]="Success";
    	}
    	elseif ($Result==-1)
    	{
    	    $out[Err]="1";
    	    $out[Error]="You are already in channel!";
    	}
    	elseif ($Result==-2)
    	{
    	    $out[Err]="2";
    	    $out[Error]="Nickname already exists in channel!";
    	}
    	return $out;
    }
}
?>
