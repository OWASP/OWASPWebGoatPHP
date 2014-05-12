<?php
class jFormDropdown extends jFormWidget
{
	/**
	 * Holds the radio options as key/value pairs
	 * @var array
	 */
	public $Items=array();
	/**
	 * Construct a radio button set
	 * @param jWidget $Parent
	 * @param string $Label
	 * @param array $Items the first element is the default one. If keys are omitted, values serve as both text and data of options.
	 */
	function __construct(jWidget $Parent,$Label=null,array $Items)
	{
		parent::__construct($Parent,$Label);
		$this->Items=$Items;
		$IsAssoc=$this->IsAssociative($Items);
		$this->SetValidation(function ($Data) use ($Items,$IsAssoc) {
			if ($IsAssoc)
				return isset($Items[$Data]); 
			else 
				return in_array($Data,$Items); 
		});
	}
	
	protected function IsAssociative($array)
	{
		if (array_values($array)===$array) return false;
		return true;
	}
	
	function Present()
	{
		
		$this->DumpLabel();
		$index=0;
		?>	<select <?php $this->DumpAttributes();?>>
<?php
		$isAssoc=$this->IsAssociative($this->Items);
		foreach ($this->Items as $value=>$text)
		{
			if (!$isAssoc) $value=$text;
			?>		<option value='<?php echo $value;?>' id='<?php echo $this->Name()."_".$value;?>'><?php echo $text;?></option>
<?php
		} 
		 
		echo "	</select>";
		$this->DumpDescription();
	}
}