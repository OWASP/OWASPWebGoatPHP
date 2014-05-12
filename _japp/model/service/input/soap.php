<?php
class ServiceInput_soap extends BaseServiceInputFormatter 
{
	function Format($Data)
	{
		if (!isset($Data) or $Data=="")
		{
			//Bug fix on PHP 5.2.2 !
			if (!isset($HTTP_RAW_POST_DATA))
 		   		$HTTP_RAW_POST_DATA = file_get_contents('php://input');
			$Data=isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:"";
		}
		$this->App->LoadSystemModule("plugin.nusoap.nusoap");	
		$this->App->SOAPserver=new soap_server();
		$this->App->SOAPserver->parse_request($Data);
		return $this->App->SOAPserver->methodparams;			
	}
	function MethodName()
	{
		return $this->App->SOAPserver->methodname;
		
	}
	
}
?>