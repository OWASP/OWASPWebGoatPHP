<?php
namespace jf;
class View extends Model
{

	static $IterativeTemplates=true;
	static $TemplateFolder="_template";
	/**
	 * Presents a template file, e.g _template/head or _template/foot
	 * @param string $ViewModule
	 * @param string $Template name
	 * @return boolean
	 */
	private function PresentTemplate($ViewModule,$Template="head")
	{
		if (self::$IterativeTemplates)
			$Iteration = 1000;
		else
			$Iteration = 1;
		
		$n = 0;
		$Parts=explode("/",$ViewModule);
		while (  $n <= $Iteration )
		{
			$Part=array_pop($Parts);
			$templateModule = implode("/",$Parts);
			if (count($Parts) == 0) break;
			$templateModule =  $templateModule . DIRECTORY_SEPARATOR. self::$TemplateFolder . DIRECTORY_SEPARATOR. $Template;
		
			if (file_exists ( $this->ModuleFile($templateModule) ))
			{
				return include $this->moduleFile($templateModule);
				//return jf::run($templateModule,array("Append"=>$this->HeadDataAppend,"Prepend"=>$this->HeadDataPrepend));
			}
		}
		return false;
	}
	
	/**
	 * Loads the header from _template/head.php
	 *
	 * @param String $ViewModule
	 * @return boolean
	 */
	function PresentHeader($ViewModule)
	{
		return $this->PresentTemplate($ViewModule,"head");
	}
	/**
	 * Presents the _template/foot.php
	 * @param unknown_type $ViewModule
	 * @return boolean
	 */
	function PresentFooter($ViewModule)
	{
		return $this->PresentTemplate($ViewModule,"foot");
	}

	/**
	 * Starts output buffering for main content
	 */
	private function StartBuffering()
	{
		ob_start ();
	}
	
	/**
	 * end and return output buffering
	 */
	private function EndBuffering()
	{
		return ob_get_clean ();
	}

	/**
	 * Holds the view module after the call to present
	 * @var string
	 */
	private $ViewModule;
	
	/**
	 * This variable holds extra data that are appended to the head template, for adding scripts and stylesheets, etc.
	 * @var array
	 */
	protected $HeadData=array();
	/**
	 * Add something to template header
	 * @param string $DataString
	 */
	function AddToHead($DataString)
	{
		$this->HeadData[]=$DataString;
		if (jf::$RunMode->IsEmbed()) //globalizing jf_title variable which holds the title of jframework page
		{
			global $jf_head;
			$jf_head=implode("\n",$this->HeadData);
		}
	}
	/**
	 * Returns the head data of the view
	 */
	function HeadData()
	{
		return implode("\n",$this->HeadData);
	}
	
	/**
	 * Presents the view with its templates
	 * @param string $ViewModule
	 * @return boolean
	 */
	function Present($ViewModule)
	{
		if (file_exists ( $this->ModuleFile($ViewModule) ))
		{
			$this->ViewModule=$ViewModule;
			
			$this->StartBuffering ();
			include $this->ModuleFile($ViewModule);
			$MainContent = $this->EndBuffering ();

			$this->PresentHeader ($ViewModule);

			echo $MainContent;

			$this->PresentFooter ($ViewModule);
			
			return true;
		}
		else
			return false;
	}


	/**
	 * Represents a portion of a view
	 * This is useful for huge views.
	 *
	 * @param String $RelativePath the path from here to the other portion of the view
	 */
	function Represent($RelativePath)
	{
		$Parts=explode("/",$this->ViewModule);
		array_pop($Parts);
		$NewParts=explode("/",$RelativePath);
		$Parts=array_merge($Parts,$NewParts);
		$NewModule=implode("/",$Parts);
		include $this->ModuleFile($NewModule);
	}
	
	/**
	 * Title of the HTML page
	 * @var string
	 */
	private $Title;
	/**
	 * Sets the page title
	 * @param string $title
	 */
	function SetTitle($title)
	{
		if (jf::$RunMode->IsEmbed()) //globalizing jf_title variable which holds the title of jframework page
		{
			global $jf_title;
			$jf_title=$title;
		}
			  
		$this->Title=$title;
	}
	/**
	 * Returns the current title of the page
	 * @return string
	 */
	function Title()
	{
		return $this->Title;
	}
}

?>