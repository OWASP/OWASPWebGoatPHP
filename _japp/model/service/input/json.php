<?php
class ServiceInput_json extends BaseServiceInputFormatter 
{
	function Format($Data)
	{
		return json_decode($Data,true);
	}
	
}
?>