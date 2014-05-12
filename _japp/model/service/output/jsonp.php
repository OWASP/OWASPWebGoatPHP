<?php
class ServiceOutput_jsonp extends BaseServiceOutputFormatter 
{
	function Format($Data,$Request)
	{
		$Result=json_encode($Data);
		if (!isset($Request['jsonp']))
		{
			//JSONP callback not set error
			$JSONP="callback";
			return "alert('jFramework Service Error: JSONP call without JSONP callback parameter set.')";
		}
		else
			$JSONP=$Request['jsonp'];
			$Result=$JSONP." ( ".$Result." );";
		$this->Headers=array("content-type"=>"text/javascript");
		return $Result;
	}

	//This automatically shows on help request for this service
	function HelpNotice()
	{
		return "پارامتر jsonp باید برای اینگونه خروجی تنظیم گردد، در غیر اینصورت یک پیغام خطا نمایش داده می شود.";
	}
}
?>