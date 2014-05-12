<?php
/**
 * File upload field
 * @author abiusx
 *
 */
class jFormUpload extends jFormWidget
{
	private $fileIsSaved=false;
	function Put($Where)
	{
		$this->fileIsSaved=true;
		if (!isset($_FILES[$this->Name()])) return false;
		$file=$_FILES[$this->Name()];
		if ($file["error"] > 0) return $file['error'];
		if ($this->MaxSize !==null && $file['size']>$this->MaxSize) return UPLOAD_ERR_FORM_SIZE;
		return move_uploaded_file($file['tmp_name'], $Where);
		
	}

	public $MaxSize=null;
	/**
	 * 
	 * Note: you should explicitly call Put method of this object to put the uploaded file
	 * @param jWidget $Parent
	 * @param string $Label
	 * @param string $Location where to put uploaded file
	 * @param string $MaxSize
	 * 
	 */
	function __construct(jWidget $Parent,$Label=null,$MaxSize=null)
	{
		parent::__construct($Parent,$Label);
		$this->MaxSize=$MaxSize;
		$Name=$this->Name();
		$this->SetValidation(function ($Data)  use ($MaxSize,$Name) {
			if (!isset($_FILES[$Name])) return false;
			$file=$_FILES[$Name];
			if ($file["error"] > 0) return $file['error'];
			if ($MaxSize !==null && $file['size']>$this->MaxSize) return UPLOAD_ERR_FORM_SIZE;
			return file_exists($file['tmp_name']);
		});
		
	}
	
	function Value()
	{
		if (isset($_FILES[$this->Name()]))
			return $_FILES[$this->Name()]['name'];
		else
			return null;
		
	}


	function Present()
	{
		$this->DumpLabel();
		?><input type="file" <?php $this->DumpAttributes();?> <?php if ($this->MaxSize!==null) echo " maxlength='".($this->MaxSize*1)."'";?> />
		<?php $this->DumpDescription();
	}
}