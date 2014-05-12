<?php
class InvalidValidationException extends Exception {}
/**
 * This is the base class for all form widgets
 * contains general form methods
 * @author abiusx
 *
 */
abstract class jFormWidget extends jWidget
{
	
	
	/**
	 * Holds widget value
	 * @var mixed|null
	 */
	protected $Value=null;
	/**
	 * Sets value of this widget
	 * @param array|mixed $FormData associative array of form data, or a variable providing only value of this instance
	 */
	function SetValue($FormData)
	{
		if (is_array($FormData))
		{
			if (isset($FormData[$this->Name()])) //this widget value is provided
				$this->Value=$FormData[$this->Name()];
			if (!$this->IsTerminal())
				foreach ($this->Children as $child)
					$child->SetValue($FormData); //call setValue on all children
		}
		else
			$this->Value=$FormData;
	}

	/**
	 * Returns the value of this instance. 
	 * @return mixed|null
	 */
	function Value()
	{
		return $this->Value;
	}
	/**
	 * Holds widget description
	 * @var string|null
	 */
	protected $Description=null;
	/**
	 * Sets widget description
	 * @param string $Description
	 */
	function SetDescription($Description)
	{
		$this->Description=$Description;
	}
	/**
	 * Holds the closure used for validation
	 * @var Closure
	 */
	protected $Validation=null;
	/**
	 * Sets a validation method for an input widget
	 * If validation is set via regex, it is also checked in javascript. Otherwise its server-side.
	 *  
	 * @param string|Closure $Validation a regex string, or a closure which receives value and returns boolean
	 * @throws InvalidValidationException when invalid regex and no closure
	 */
	function SetValidation($Validation)
	{
		if (is_string($Validation) and preg_match($Validation,"")!==false) //regex mode, and valid regex
		{
			$this->Validation=function ($Data) use ($Validation) { 
				return preg_match($Validation,$Data)==1;
			};
		}
		elseif (is_object($Validation) and ($Validation instanceof Closure))
		{
			$this->Validation=$Validation;
		}
		else
			throw new InvalidValidationException("You can only provide a regex or a closure for validation.");
	}
	
	/**
	 * Tells whether or not content of this input field are valid
	 * @return boolean|null true if valid, false if invalid, null if unknown
	 */
	function IsValid()
	{
		if ($this->Value()===null) //no data
			return null;
		elseif ($this->Validation!==null)
		{
			$temp=$this->Validation;
			return ($temp($this->Value())?true:false);
		}
		else //no validation needed, everything is valid
			return true;
	}
	
	/**
	 * Most form widgets are usable stand-alone
	 * (non-PHPdoc)
	 * @see jWidget::IsRootable()
	 */
	protected function IsRootable()
	{
		return true;
	}
	public $Label=null;
	/**
	 * Create a form widget
	 * @param jWidget $Parent (usually the form)
	 * @param string $Label text of the widget
	 */
	function __construct(jWidget $Parent,$Label=null)
	{
		parent::__construct($Parent);
		$this->Label=$Label;
	}
	/**
	 * Most form widgets are terminal. Those who are not, override this.
	 * (non-PHPdoc)
	 * @see jWidget::IsTerminal()
	 */
	protected function IsTerminal()
	{
		return true;
	}
	
	
	
	/**
	 * Output the label belonging to this widget. Those who do not have labels, override this.
	 */
	protected function DumpLabel()
	{
		if ($this->Label):
		?>	<label class='jWidget_label' for='<?php echo $this->Name()?>'><?php echo $this->Label;?></label>
<?php	endif;
			
	}
	
	/**
	 * Shortcut to all dump functions
	 * Dumps Name, ID, Class, Value and Style for a typical input attributes
	 */
	protected function DumpAttributes()
	{
		echo "{$this->DumpName()} {$this->DumpID()} {$this->DumpClass()} {$this->DumpValue()} {$this->DumpStyle()}";	
	}
	/**
	 * Dumps value of a form input
	 */
	protected function DumpValue()
	{
		
		if ($this->Value()!==null):
		?>	value='<?php exho($this->Value());?>' <?php 
		endif;
	}		
	/**
	 * Dump name of a form field
	 */
	protected function DumpName()
	{
		?>	name='<?php exho($this->Name());?>' <?php 
	}
	/**
	 * Dump ID of the field
	 */
	protected function DumpID()
	{
		?>	id='<?php exho($this->Name());?>' <?php 
	}
	/**
	 * Dump CSS class
	 * (non-PHPdoc)
	 * @see jWidget_HTML::DumpClass()
	 */
	protected function DumpClass()
	{
		echo "class='jWidget {$this->Class}".($this->IsValid()===false?" jWidget_invalid":"")."'";
	}
	/**
	 * Dump description of the input
	 */
	protected function DumpDescription()
	{
// 		if ($this->Description)
		echo "<span class='jWidget_description'>{$this->Description}</span>\n";
	}
	
	/**
	 * Mostly javascript codes for input validation and submit validation
	 * (non-PHPdoc)
	 * @see jWidget_HTML::JS()
	 */
	function JS()
	{
		if ($this->IsFirstTime(__CLASS__)):;
		?>
var validationRules={"balls":"yes"};
function validate(e)
{
	var obj=e;
	if (e.target)
		obj=e.target;
	var value=$(obj).val();
	
	//$(obj).addClass("jWidget_valid");
	if (obj.id in validationRules)
	{
		if (validationRules[obj.id].test(value))
		{
			$(obj).removeClass("jWidget_invalid");
			return true;
		}
		else
		{
			$(obj).addClass("jWidget_invalid");
			$(obj).removeClass("jWidget_valid");
			return false;
		}
	}
	return true;
}
function addValidationRule(id,regex)
{
	if (regex.substring(0,1)=="/")
	{
		regex=regex.substring(1);
		regex=regex.substring(0,regex.length-1);
	}
	this.validationRules[id]=new RegExp(regex);
}
$(function(){
	$(".jWidget_description").hide();
	$(".jForm :input").on("blur",validate);
});
<?php endif;
	}
	
	function CSS()
	{
		if (!$this->IsFirstTime(__CLASS__)) return;
		?>
.jWidget_label {
	float:left;
	clear:left;
	width:200px;
			
}
.jFormFrame {
	padding:20px;
	margin:10px;
	border:1px gray solid;
	width:auto;
}
.jWidget_valid:not([type='submit']) {
	background-image: url("<?php echo jf::url();?>/img/jwidget/valid.png");
	background-position: top right;
	background-repeat: no-repeat;
	background-color: #FAFFFA;
}
select.jWidget_valid {
	background-image: none !important;
	-webkit-appearance: menulist !important;
}
.jWidget_invalid {
	background-image: url("<?php echo jf::url();?>/img/jwidget/invalid.png");
	background-position:top right;
	background-repeat: no-repeat;
	background-color: #FFFAFA;
	}
.jWidget_description {
	font-size:small;
	color:#444444;
}
.jForm .jWidget_container {
	padding:5px;
	border-radius:5px;
}
.jForm .jWidget_container.selected {
	background-color:#DDDDDD;
	border:1px solid gray;
	padding:4px;
}
<?php 
	}

	
}
