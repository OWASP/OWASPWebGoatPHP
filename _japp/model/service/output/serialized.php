<?php
class ServiceOutput_serialized extends BaseServiceOutputFormatter 
{
	function Format($Data,$Request)
	{
		$Result=serialize($Data);
		return $Result;
		
	}
	
}
?>