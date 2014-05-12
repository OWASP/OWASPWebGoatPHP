<?php
/**
 * One-line text input field for a form
 * @author abiusx
 *
 */
class jFormInput extends jFormWidget
{
	function JS()
	{
		if ($this->JavascriptValidation)
		?>
$(function(){
	addValidationRule('<?php echo $this->Name();?>','<?php echo $this->JavascriptValidation;?>');
		<?php if ($this->IsFirstTime()):?>
		<?php endif;?>
});
		<?php
	}
	private $JavascriptValidation=null;
	function SetValidation($Validation)
	{
		if (is_string($Validation) and preg_match($Validation,"")!==false)
		{	
			$res=str_replace("\\","\\\\",$Validation);
			$res=str_replace("'","\\'",$res);
			$this->JavascriptValidation=$res; //regexp
		}
		parent::SetValidation($Validation);
	}
	
	function Present()
	{
		
		$this->DumpLabel();
		?>	<input  type='text' <?php $this->DumpAttributes();?>/>
<?php $this->DumpDescription();
	}
	
		
}