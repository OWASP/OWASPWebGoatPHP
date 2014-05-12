<?php
abstract class BaseServiceInputFormatter
{
	public $App;
	function __construct(jfBaseFrontController $App)
	{
		$this->App=$App;
	}
	abstract function Format($Data);
}
?>