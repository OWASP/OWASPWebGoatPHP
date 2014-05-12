<?php
class jForm extends jWidget
{
	const Method_GET="get";
	const Method_POST="post";

	public $Action=null;
	/**
	 * Sets form data.
	 * Should be done after all widgets are instantiated.
	 * @throws Exception if no form data provided and the method is now known
	 * @param array $FormData
	 */
	function SetData(array $FormData=null)
	{
		if ($FormData===null)
			if ($this->Method==jForm::Method_POST)
			$FormData=$_POST;
		elseif ($this->Method==jForm::Method_GET)
		$FormData=$_GET;
		else
			throw new Exception("Unknown source of form data. Please provide form data manually.");
		if ($FormData===null) throw new Exception("No form data provided to set.");
		foreach ($this->Children as $child)
			$child->SetValue($FormData);
	}
	/**
	 * The form submit method. Possible constants are jForm::Method_*
	 * @var string
	 */
	public $Method;

	/**
	 * Checks for validation of form input fields based on their validation closures.
	 * @return boolean|null null if no data available for validation
	 */
	function IsValid()
	{
		foreach ($this->Children as $child)
		{
			if ($child->IsValid()===false)
				return false;
		}
		return true;
	}
	
	/**
	 * Generates CSRF token and adds a hidden field to the form.
	 */
	private function CSRFGuard()
	{
		$csrf=new jFormCsrf($this,$this->Name()."_csrf");
	}

	public $Label=null;
	function __construct($Parent=null,$Label=null,$Method=jForm::Method_POST)
	{
		parent::__construct($Parent);
		$this->Label=$Label;		
		$this->Method=$Method;
		$this->CSRFGuard();
	}
	protected function IsRootable()
	{
		return true;
	}
	protected function IsTerminal()
	{
		return false;
	}
	function Present()
	{
		?>
<fieldset class='jFormFrame' id='<?php echo $this->Name();?>_frame'>
	<?php if ($this->Label) {?><legend><?php exho ($this->Label);?></legend><?php }?>
<form <?php echo $this->DumpClass();?> name='<?php echo $this->Name();?>' id='<?php echo $this->Name();?>'<?php 
if ($this->Action) {?> action="<?php exho($this->Action);?>" <?php } 
?> method='post' enctype='multipart/form-data'>
	<?php
		$this->PresentChildren();
		?>
</form>
</fieldset>
		<?php
	}
	function JS()
	{
		if ($this->IsFirstTime(__CLASS__)):
		?>
function validateForm(formObject)
{
	if (formObject.target)
		formObject=formObject.target;
	var formName=formObject.name;
	var res=true;
	var firstInvalid=null;
	$("#"+formName+" input[type='submit']").attr("disabled",true);
	$("#"+formName+" :input").each(function(i){
		if (validate(this)==false)
		{
			if (res==true) firstInvalid=this;
			res=false;
		}
	});
	if (res==false) $(firstInvalid).focus();
	$("#"+formName+" input[type='submit']").attr("disabled",false);
	return res;
}
function showDescription(e)
{
	if (e.target)
		e=e.target;
	$(e).parent(".jWidget_container").addClass("selected");
	$(e).nextAll(".jWidget_description").first().fadeIn("fast");
	$(e).removeClass("jWidget_valid");
}
function hideDescription(e)
{
	if (e.target)
		e=e.target;
	$(e).parent(".jWidget_container").removeClass("selected");
	$(e).nextAll(".jWidget_description").first().fadeOut();
}
<?php endif; ?>
$(function(){
	$("#<?php echo $this->Name();?>").bind("submit",validateForm);
	$("#<?php echo $this->Name();?> :input").bind("focus",showDescription);
	$("#<?php echo $this->Name();?> :input").bind("blur",hideDescription);
	$("#<?php echo $this->Name();?> :input.jWidget_invalid").first().focus();
});
<?php			
		
	}
	
}
