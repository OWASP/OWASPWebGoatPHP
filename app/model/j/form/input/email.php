<?php
/**
 * One-line number input field for a form
 * @author abiusx
 *
 */
class jFormInputEmail extends jFormInput
{

	function __construct(jWidget $Parent,$Label=null)
	{
		parent::__construct($Parent,$Label);
		$this->SetValidation('/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/');
		
	}
}