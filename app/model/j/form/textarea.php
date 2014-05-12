<?php
/**
 * One-line text input field for a form
 * @author abiusx
 *
 */
class jFormTextarea extends jFormWidget
{
	
	function Present()
	{
		
		$this->DumpLabel();
		?>	<textarea <?php $this->DumpAttributes();?>></textarea>
<?php $this->DumpDescription();
	}
	
		
}