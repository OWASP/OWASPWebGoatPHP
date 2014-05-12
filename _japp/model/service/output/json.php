<?php
class ServiceOutput_json extends BaseServiceOutputFormatter 
{
	function Format($Data,$Request)
	{
		$Result=json_encode($Data);
		$this->Headers=array("content-type"=>"text/javascript");
		return $Result;
	}
	
}
?>