<?php
class ServiceOutput_soap extends BaseServiceOutputFormatter 
{
	function Format($Data,$Request)
	{
		
	
		if (!isset($this->App->SOAPserver))
		{
			$this->App->LoadSystemModule("plugin.nusoap.nusoap");
			$this->App->SOAPserver=new soap_server();
		}
		$this->App->SOAPserver->methodreturn=$Data;
		$this->App->SOAPserver->serialize_return();
		ob_start();
		$this->App->SOAPserver->send_response();
		$Result=ob_get_contents();
		ob_end_clean();
		$this->Headers=$this->App->SOAPserver->outgoing_headers;
		$this->Headers[0]="Server: jFramework SOAP server";
		unset($this->Headers[1]);
		return $Result;
	}
}
?>