<?php
class ServiceInput_xml extends BaseServiceInputFormatter 
{
	function Format($Data)
	{
		$XML=new XMLConvertor();
		return $XML->xml_to_array($Data);
	}
	
}
?>