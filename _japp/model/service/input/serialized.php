<?php
class ServiceInput_serialized extends BaseServiceInputFormatter 
{
	function Format($Data)
	{
		return unserialize($Data);
	}
	
}
?>