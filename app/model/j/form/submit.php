<?php
class jFormSubmit extends jFormWidget
{
	
	function __construct($Parent,$Label)
	{
		parent::__construct($Parent,$Label);
		$this->SetValue($Label);
	}
	protected function IsRootable()
	{
		return false;
	}
	
	function Present()
	{
		?>	<input type='submit' <?php $this->DumpAttributes();?>/>
		<?php  $this->DumpDescription(); 
	}
}