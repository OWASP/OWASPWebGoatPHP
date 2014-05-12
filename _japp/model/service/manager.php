<?php
//Service General Controller
//sample: http://localhost/jdiary/service.diary.store?method=post&output=jsonp&input=soap&jsonp=sth&postdata=postdatafield
namespace jf;
/**
 * 
 * Service Controller
 * @author abiusx
 * @version 3.03
 */
class ServiceManager extends Model
{
    /**
     * Headers outputed by ServiceCore
     *
     * @var String
     */
    public $Headers;
    function __construct ()
    {
    	jf::import("jf/plugin/phpxml");
    }
    
    /**
     * Extracts input from service call
     *
     * @param Array $BaseParams
     * @param String $InputType
     * @return Array
     */
    private function ExtractInput (&$BaseParams, $InputType,&$MethodName=null)
    {
    	$InputParams = $this->FormatInput($BaseParams['data'], $InputType,$MethodName);
        unset($BaseParams['data']);
        if (is_array($BaseParams) and is_array($InputParams))
            $Params = array_merge($BaseParams, $InputParams);
        elseif (is_array($BaseParams))
            $Params = $BaseParams;
        elseif (is_array($InputParams))
            $Params = $InputParams;
        return $Params;
    }
    /**
     * Calls a local service as a function (in matters of speed)
     *
     * @param String $ServiceExternalName
     * @param mixed $Params
     * @param String $InputType
     * @param String $OutputType
     * @return mixed
     */
    function Invoke ($ServiceExternalName, $Params = null, $InputType = "array", $OutputType = "array")
    {
        if (isset($Params['output']))
        {
            $OutputType = $Params['output'];
            unset($Params['output']);
        }
        if (isset($Params['input']))
        {
            $InputType = $Params['input'];
            unset($Params['input']);
        }
        return $this->Serve($ServiceExternalName, $Params, $InputType, $OutputType);
    }
    
    /**
     * Calls a service amongst this jFramework Distributions.
     * This function is blind, thus takes a lot of time to call all distributions and retrieve results.
     * State: Experimental
     *
     * @param String $ServiceTitle
     * @param Mixed $Params
     * @param String $InputType
     * @param String $OutputType
     * @return Mixed
     */
    function SummonService ($ServiceTitle, $Params = null, $InputType = "array", $OutputType = "array")
    {
        $DS = new DistributedServers($this->App);
        list (, $Res) = $this->InvokeService($ServiceTitle, $Params, $InputType, $OutputType);
        if (isset($Res["Error"]))
        {
            foreach ($DS->Servers() as $ServerName => $ServerUrl)
            {
                if ($ServerName == "this")
                    continue;
                $this->CallService($ServerUrl . "/service." . $ServiceTitle, $Params, $InputType, $OutputType);
                if (! isset($Res["Error"]))
                    break;
            }
        }
        if (isset($Res['Error']))
        {
            return array("Error" => "No such service found in any distributed servers!");
        }
        return $Res;
    }
    /**
    Wraps another service in this function
		_deprecated
    The other services might not be on the local server, Uses SERVICES_INTERMEDIATE_ENCODING for wrapping of service.
    @param ServiceURL Address of service
    @param BaseParams Basic parameters of calling a serivce
    @param InputType Input format of the data
	@param OutputType Output format of the data
	@return Mixed result of service call
     */
    
    /**
     * Calls a web service on any URL
     * Uses constant("SERVICES_INTERMEDIATE_ENCODING") for wrapping, which is SOAP by default
     * 
     * @param String $Endpoint WSDL
     * @param string $Method name
     * @param Array $Params
     * @return Mixed
     */
    function CallSoap ($Endpoint, $Method, $Params,$isWSDL=false,$Session=false)
    {
    	j::$App->LoadSystemModule("plugin.nusoap.nusoap");	
    	$soap=new nusoap_client($Endpoint
    	,$isWSDL
    	);
    	$CookiePath=$this->Hostname($Endpoint)."_cookies";
		if ($Session)
			$soap->UpdateCookies(j::LoadSession($CookiePath));
    	$Result=$soap->call($Method,$Params);
    	if ($Session)
    		j::SaveSession($CookiePath, $soap->getCookies());
    	return $Result;
    }
    private function Hostname($Endpoint)
    {
    	$Host=substr($Endpoint,0, strpos($Endpoint,"/",strpos($Endpoint,"/")+2));
		return $Host;
    }
    
    
    /**
     * 
     * Alias for CallSoap atm
     * @param String $Endpoint WSDL
     * @param string $Method name
     * @param Array $Params
     * @return Mixed
     */
    function CallService($Endpoint=null, $Method=null, $Params=null,$isWSDL=null,$Session=null)
    {
    	$args=func_get_args();
    	return call_user_func_array(array($this,"CallSoap"),$args);
    }
    /**
     * Serves a local service, i.e loads the class and runs the service with given parameters
     *
     * @param String $ServiceName
     * @param mixed $BaseParams
     * @param String $InputType
     * @param String $OutputType
     * @return Mixed false on failure
     */
    function Serve ($ServiceName, $BaseParams, $InputType, $OutputType)
    {
        $ServiceTitle = new jpModule2ClassName($ServiceName);
        $ServiceTitle = $ServiceTitle->__toString();
        $ReflexInput = $BaseParams["reflect"];
        unset($BaseParams["reflect"]);
	
        
        //RAW_HTTP_POST
        if (!isset($HTTP_RAW_POST_DATA))
 		   	$HTTP_RAW_POST_DATA = file_get_contents('php://input');
		$Data=$HTTP_RAW_POST_DATA;
        if ($Data)
        	$BaseParams['data']=$Data;

        //Evaluating Service Parameters, HTTP Binding
        	$Params = $this->ExtractInput($BaseParams, $InputType,$MethodName);
        
        //Enumerating Service
        if (!$this->App->LoadSystemModule($ServiceName,true))
        {
            if (!$this->App->LoadModule($ServiceName,true))
                return false;
        }
        $OldResult=$Result=mt_rand(1,100000);
        if (class_exists($ServiceTitle))
        {
            $ServiceObject = new $ServiceTitle($this->App);
            if (isset($BaseParams['WSDL']) or isset($BaseParams['wsdl']))
            {
				return $this->PresentWSDL($ServiceObject,$ServiceTitle);
            }
            else
            {
				if ($ServiceTitle=="DispatchService")
				{
					if ($MethodName)
						$Result = $ServiceObject->Serve($ServiceTitle,$MethodName,$Params);
					else
						return $this->PresentWSDL($ServiceObject, $ServiceTitle);
				}
				else
				{            	
            		$Result = $ServiceObject->Execute($Params);
				}
            }
        }
        else
        {
        	if (!is_array($Result))
        		$Result=array("Result"=>$Result);
            $Result['Error'] = "Service not found.";
        }
        if ($Result===$OldResult)
        {
            $Result = array();
            $Result['Error'] = "Service did not respond.";
        }
        //Add invoke parameters to the result if there's an error
        if ($ReflexInput)
        {
            $Result['Input'] = $Params;
            $Result['Input']['Service'] = $ServiceName;
            $Result['Input']['InputType'] = $InputType;
            $Result['Input']['OutputType'] = $OutputType;
        }
        //Formatting Output
        $Result = $this->FormatOutput($Result, $OutputType, $BaseParams);
        return $Result;
    }
    /**
     * 
     * Converts an array of types to XSD: types
     * @param Array $Array
     */
    function TypeArray2WSDLTypes($Array)
    {
    	if (is_array($Array))
    	{
    		foreach ($Array as $k=>&$v)
    		{
    			if ($v=="string")
    				$v="xsd:string";
    			elseif ($v=="integer" or $v=="int")
    				$v="xsd:int";
    			elseif ($v=="array")
    				$v="xsd:array";
    			elseif ($v=="byte")
    				$v="xsd:byte";
    			elseif ($v=="uint")
    				$v="xsd:unisignedInt";
    			elseif ($v=="double" or $v=="float")
    				$v="xsd:double";
    			else
    				$v="xsd:any";
    			
    			
    		}
    	}
    	else
    	$Array=array();
    	return $Array;
    }
    
    function PresentWSDL($ServiceObject,$ServiceTitle)
    {
    	if (get_class($ServiceObject)=="DispatchService")
    	{
    		return $ServiceObject->PresentWSDL();
    	}
    	
    	$ServiceTitle=substr($ServiceTitle,0,strlen($ServiceTitle)-strlen("Service"));
    	
        if (method_exists($ServiceObject,"Help"))
        	$Documentation=$ServiceObject->Help();
        else
        	$Documentation=null;
    	

        if (method_exists($ServiceObject,"In"))
        	$In=$ServiceObject->In();
        if (method_exists($ServiceObject,"Out"))
        	$Out=$ServiceObject->Out();
        
        $In=$this->TypeArray2WSDLTypes($In);
        $Out=$this->TypeArray2WSDLTypes($Out);
        
    	j::$App->LoadSystemModule("plugin.nusoap.nusoap");	
		$soap=new soap_server();
		$Namespace=SiteRoot.constant("jf_jPath_Request_Delimiter"). "service".constant("jf_jPath_Request_Delimiter");
//		$Endpoint=$Namespace."dispatch";
		$Endpoint=jURL::URL(false); //SoapClients use this to send request!
		$soap->configureWSDL(
			$ServiceTitle,
			$Namespace,
			$Endpoint);
//			$Namespace);
		$soap->register($ServiceTitle,$In,$Out,$Namespace,$SoapAction=$ServiceTitle,null,null,nl2br($Documentation),null);
		$soap->service("");
		return true;
    	
    }
    /**
     * Converts input data into associative array to be used by service class
     *
     * @param Mixed $Data
     * @param String $Type
     * @return Array
     */
    private function FormatInput ($Data, $Type,&$MethodName=null)
    {
        $this->App->LoadSystemModule("model.services.input.base"); //base output
        $this->App->LoadSystemModule("model.services.input.$Type");
        $ClassName = "ServiceInput_$Type";
        if (class_exists($ClassName))
        {
            $InputObject = new $ClassName($this->App);
            $Result = $InputObject->Format($Data);
            if (method_exists($InputObject, "MethodName"))
            	$MethodName=$InputObject->MethodName();
        }
        else
            $Result['Error']['InputFormat'] = "Input format not applicable.";
        return $Result;
    }
    /**
     * Convets associative array into desired format
     *
     * @param Array $Data
     * @param String $Type
     * @param Array $Request The whole request parameters which for JSONP calls, has $Request['jsonp'] as callback
     * @return String
     */
    private function FormatOutput ($Data, $Type, $Request)
    {
        $this->App->LoadSystemModule("model.services.output.base"); //base output
        $this->App->LoadSystemModule("model.services.output.$Type");
        $ClassName = "ServiceOutput_$Type";
        if (class_exists($ClassName))
        {
            $OutputObject = new $ClassName($this->App);
            $Result = $OutputObject->Format($Data, $Request);
        }
        else
            $Result = "Output format not applicable.";
        $this->Headers = $OutputObject->Headers;
        return $Result;
    }
}
