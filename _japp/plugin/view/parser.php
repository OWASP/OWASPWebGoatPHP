<?php
#TODO: optimize this
class ViewParserPlugin extends BasePluginClass
{
	public $Title;
	function GetRipTitle(&$Data)
	{
		#<title>		
		preg_match("/<title>(.*?)<\/title>/i",$Data,$Title);
		if (array_key_exists(1, $Title))
		{
			$this->Title=$Title[1];
			preg_replace("/<title>(.*?)<\/title>/i","",$Data);
		}
	}
	function GetRipMeta(&$Data)
	{
		#<meta
		preg_match_all("/<meta\\s*name=[\"'](.*?)[\"']\\s*content=[\"'](.*?)[\"']\s*\/>/i"
		,$Data,$m, PREG_SET_ORDER  );
		$this->Meta=$m;
		
		$Data=preg_replace("/<meta\\s*name=[\"'](.*?)[\"']\\s*content=[\"'](.*?)[\"']\s*\/>/i"
		,"",$Data);
		
	}
	function FixLinks(&$Data)
	{
		# href
		$Data=preg_replace("/(href=[\"'])(\/.*[\"'])/i"
		,"$1".SiteRoot."$2",$Data);
		$Data=preg_replace("/(src=[\"'])(\/.*[\"'])/i"
		,"$1".SiteRoot."$2",$Data);
		$Data=preg_replace("/(background-image\\s*:\\s*url\\s*\\([\"']?)\//i"
		,"$1".SiteRoot."/",$Data);
	}
	function FormatMeta()
	{
		$Extra="";
		if ($this->Meta)
		foreach ($this->Meta as $M)
			$Extra.="<meta name='{$M[1]}' content='{$M[2]}' />\n";
		return $Extra;
	}
	
	function Parse(&$Data)
	{
		$this->GetRipTitle($Data);
		$this->GetRipMeta($Data);		
		$this->FixLinks($Data);
		
	}	
	
}

?>