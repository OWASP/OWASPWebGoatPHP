<?php
class DispatchService extends JService
{
	function Execute($Params)
	{

		return "Error: This service should not be executed. It's only an endpoint dispatcher for other services.";
	}
	
	function Serve($ServiceTitle,$MethodName,$Params)
	{
		if ($MethodName=="")
			return $this->PresentWSDL();
			
		$Classname=$MethodName."Service";
		$ServiceObject=new $Classname;
		return $ServiceObject->Execute($Params);
	}
	private function Register($ServiceTitle,$SoapServer,$Namespace)
	{		
		$Classname=$ServiceTitle;
		$ServiceTitle=substr($ServiceTitle,0,strlen($ServiceTitle)-strlen("Service"));
    	
		if (class_exists($Classname)) $ServiceObject= new $Classname();

		
		if (method_exists($ServiceObject,"Help"))
        	$Documentation=$ServiceObject->Help();
        else
        	$Documentation=null;
    
        	$AnotherEndpoint=new jpClassName2Module($Classname);
        	$AnotherEndpoint=SiteRoot.constant("jf_jPath_Request_Delimiter").str_replace(".", constant("jf_jPath_Request_Delimiter"), $AnotherEndpoint);
    	
	$Documentation.=("\nThis service is also solely available at endpoint \n".$AnotherEndpoint);
	    
        if (method_exists($ServiceObject,"In"))
        	$In=$ServiceObject->In();
        if (method_exists($ServiceObject,"Out"))
        	$Out=$ServiceObject->Out();
        
        $In=j::$Services->TypeArray2WSDLTypes($In);
        $Out=j::$Services->TypeArray2WSDLTypes($Out);
        
		$SoapServer->register($ServiceTitle,$In,$Out,$Namespace,$SoapAction=$ServiceTitle,null,null,nl2br($Documentation),null);
		return true;
	}
	function Help()
	{
		return "The dispatch service acts as a central access point to all services, and it also provides
			list of available services and their WSDLs if necessary.";
	}
	public function PresentWSDL()
	{
		    	
			
		$Namespace=SiteRoot.constant("jf_jPath_Request_Delimiter"). "service".constant("jf_jPath_Request_Delimiter");
		$Endpoint=HttpRequest::URL(false); //SoapClients use this to send request!

		j::$App->LoadSystemModule("plugin.nusoap.nusoap");	
		$soap=new soap_server();
		$soap->configureWSDL(
			"Dispatch",
			$Namespace,
			$Endpoint);
//			$Namespace);

		$r=$this->ListFiles(dirname(__FILE__));
		foreach ($r as $k=>$v)
			$this->Register($this->File2Class($v),$soap,$Namespace);
		$soap->service("");
		return true;
	}
	
	function File2Class($File)
	{
		$LastPart=substr($File,strlen(dirname(__FILE__))+1);
		$LastPart=substr($LastPart,0,strlen($LastPart)-4);
		$Module="service.".str_replace("/",".",$LastPart);
		$Classname=new jpModule2ClassName($Module);
		return $Classname."";
	}
	
	function ListFiles($Path)
	{
		$di=new DirectoryIterator($Path);
		$out=array();
		foreach ($di as $k=>$v)
		{
			
			if ($v=="." or $v=="..") continue;
			$FullPath=$Path."/".$v;
			if (is_file($FullPath))
			{
				if (strtolower(substr($FullPath,strlen($FullPath)-4))==".php")
					$out[]=(string)$FullPath;
			}
			elseif (is_dir($FullPath))
				$out=array_merge($out,$this->ListFiles($FullPath));
//				$out[]=$this->ListFiles($Path."/".$v);
		}
		return $out; 		
	}
	
}