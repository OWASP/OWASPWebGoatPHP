<?php
class ServiceOutput_xml extends BaseServiceOutputFormatter 
{
	function Format($Data,$Request)
	{
    	$XML=new XMLConvertor();
    	$Result=$XML->array_to_xml($Data);
		$this->Headers=array("content-type"=>"text/xml");
    	return $Result;
		
	}
	
}
?>