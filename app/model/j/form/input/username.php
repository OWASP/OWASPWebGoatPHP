<?php
/**
 * One-line username input field for a form
 * Username follows identifier rules
 * @author abiusx
 *
 */
class jFormInputUsername extends jFormInput
{

	function __construct(jWidget $Parent,$Label=null,$MinLength=3)
	{
		parent::__construct($Parent,$Label);
		$this->SetValidation('/^[a-zA-Z][a-zA-Z0-9_]{'.($MinLength-1).',}$/');
		
	}
}