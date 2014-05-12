<?php
class jFormCsrf extends jFormWidget
{
	
	
	const SettingNamePrefix="jWidget_CSRFGuard_";
	
	protected $Token=null;
	/**
	 * Construct a CSRF guard hidden field. You should provide the name of the csrf guard as second parameter here.
	 * @param jWidget $Parent
	 */
	function __construct(jWidget $Parent,$Name)
	{
		$this->__setname($Name);
		parent::__construct($Parent);
		$this->SetValidation(
				function ($Data)  use ($Name){ 
					return jf::LoadSessionSetting(jFormCsrf::SettingNamePrefix.$Name)==$Data;
				}
		);
	}
	
	
	function Present()
	{
		//only update the csrf token on the session when outputting the field.
		$this->Token=jf::$Security->RandomToken();
		jf::SaveSessionSetting(jFormCsrf::SettingNamePrefix.$this->Name(), $this->Token);
		
		echo "<input class='jWidget jFormCSRF' type='hidden' name='{$this->Name()}' value='{$this->Token}' />\n";
	}
}