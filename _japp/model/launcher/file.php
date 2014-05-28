<?php
namespace jf;
/**
 * File launcher. Gets a request (of type file) and feeds the file
 * @author abiusx
 * @version 1.0
 */
class FileLauncher extends BaseLauncher
{
	public static $StaticContentPrefix = array ("img" => "images", "images"=>"images",
			"image"=>"images", "files"=>"files", "file" => "files", "script" => "script", "style" => "style",
            "fonts" => "fonts");
	protected $Request=null;
	function  __construct($Request)
	{
		$this->Request=$Request;
		if (!$this->Launch())
				jf::run ( "view/_internal/error/404");
	}
	/**
	 * Launches an application controller. Returns what the controller returns.
	 * If it is false, a not found error is displayed.
	 * @return boolean
	 */
	function Launch()
	{
		$Parts=explode("/",$this->Request);
		$Type=array_shift($Parts);
		if (!array_key_exists($Type, self::$StaticContentPrefix))
			return false;
		$Type=self::$StaticContentPrefix[$Type];
		array_unshift($Parts,$Type);

		$file=jf::root().DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,$Parts);

		$FileMan=new DownloadManager();
		return $FileMan->Feed($file);
	}

}