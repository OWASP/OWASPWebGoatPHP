<?php
class jFormFieldset extends jFormWidget
{
	
	protected function IsTerminal()
	{
		return false;
	}
	
	function Present()
	{
?><fieldset <?php $this->DumpAttributes();?>>
<legend><?php echo $this->Label; ?></legend>
<?php  $this->PresentChildren(); 
?></fieldset>
<?php 
	}
}