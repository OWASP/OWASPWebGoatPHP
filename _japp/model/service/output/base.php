<?php
abstract class BaseServiceOutputFormatter
{
	public $App;
	public $Headers; //HTTP headers on this output type
	function __construct(jfBaseFrontController $App)
	{
		$this->App=$App;
	}
	abstract function Format($Data,$Request);
		 
}
?>