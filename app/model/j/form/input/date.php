<?php
/**
 * One-line date input field for a form
 * @author abiusx
 *
 */
class jFormInputDate extends jFormInput
{

	function __construct(jWidget $Parent,$Label=null)
	{
		parent::__construct($Parent,$Label);
		$this->SetValidation('/^\d{4}-\d{2}-\d{2}$/');
		$this->SetDescription($this->Description." Enter date in YYYY-MM-DD format.");
		
	}
}