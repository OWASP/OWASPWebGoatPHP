<?php

class jFormCaptcha extends jFormWidget
{
	
	
	const SettingNamePrefix="jWidget_Form_Captcha";
	
	protected $Token=null;
	/**
	 * Construct a Captcha Field.
	 * @param jWidget $Parent
	 */
	function __construct(jWidget $Parent)
	{
		parent::__construct($Parent,"CAPTCHA");
		if (isset($_GET['CAPTCHA']) && $_GET['CAPTCHA']==$this->Name())
			$this->DumpImage();
		$obj=$this;
		$this->SetValidation(
				function ($Data)  use ($obj){
					return $obj->Validate($Data);
				}
		);
		
	}
	
	/**
	 * Generates the token for this instance. If already generated, removes and makes another,
	 * so it should be called inside dumpImage
	 */
	function Generate()
	{
		$settingName=jFormCaptcha::SettingNamePrefix.$this->Name();
// 		$list="abcdefghijkmnopqrstuvwxyz";
		$list="ABDEHJKMNOPQRSTUWXYZ";
		$text="";
		for ($j=0;$j<5;++$j)
			$text.=$list[jf::rand(0,strlen($list)-1)];
		$this->Token=$text;
		jf::SaveSessionSetting($settingName, $this->Token,\jf\Timeout::MINUTE*15);
		return $text;
	}
	private $isValid=null;
	/**
	 * Checks if CAPTCHA is valid or not. Caches the status so no matter how many times in one instance of application
	 * it is checked, it returns true.
	 * @param unknown_type $Data
	 * @return NULL
	 */
	function Validate($Data)
	{
		if ($this->isValid===null)
		{
			$this->isValid=(strtolower($Data)==strtolower(jf::LoadSessionSetting(jFormCaptcha::SettingNamePrefix.$this->Name())));
			jf::DeleteSessionSetting(jFormCaptcha::SettingNamePrefix.$this->Name());
		}
		return $this->isValid;
	}
	/**
	 * Convert text to unicode compatible
	 * @param string $text
	 * @return string
	 */
	private function UnicodeTextForImage($text)
	{
		// Convert UTF-8 string to HTML entities
		$text = mb_convert_encoding($text, 'HTML-ENTITIES',"UTF-8");
		// Convert HTML entities into ISO-8859-1
		$text = html_entity_decode($text,ENT_NOQUOTES, "ISO-8859-1");
		// Convert characters > 127 into their hexidecimal equivalents
		$out = "";
		for($i = 0; $i < strlen($text); $i++) {
			$letter = $text[$i];
			$num = ord($letter);
			if($num>127) {
				$out .= "&#$num;";
			} else {
				$out .=  $letter;
			}
		}
		return $out;
	}
	/**
	 * Outputs the image to the browser and terminates
	 */
	private function DumpImage()
	{
// 		$text=$this->Generate();
		$text=$this->UnicodeTextForImage($this->Generate());
		// 		$font=dirname(__FILE__)."/captcha/font.ttf";
		$font=__DIR__."/font.ttf";
		$fontSize=28;
		$width=($fontSize-5)*strlen($text)+40;
		$height=$fontSize+30;
		$img=imagecreatetruecolor($width,$height);
		imagealphablending($img, true);
		imagesavealpha($img, true);
		$r=mt_rand(80,235);
		$b=mt_rand(80,235);
		$g=mt_rand(80,235);
		$bgcolor=imagecolorallocatealpha($img,$r,$g,$b,0);
		imagefill($img,1,1,$bgcolor);
		$ex=-mt_rand(80,min($r,$b,$g));
// 		if (mt_rand(0,1)) $ex=-$ex;
		$b+=$ex;
		$r+=$ex;
		$g+=$ex;
		$color=imagecolorallocate($img,$r,$b,$g);
		$text=explode(" ",$text);
		$sumWidth=0;

		$b+=50;
		$g+=50;
		$r+=50;
		$elcolor=imagecolorallocatealpha($img,$r,$g,$b,20);
		//ellipses
		for ($i=0;$i<3;++$i)
		{
			$ellipseWidth=mt_rand(30,$width-50);
			$ellipsePosX=mt_rand(min($ellipseWidth,$width-$ellipseWidth),max($ellipseWidth,$width-$ellipseWidth));
			$ellipseHeight=mt_rand(10,40);
			$ellipsePosY=mt_rand(min($ellipseHeight,$height-$ellipseHeight),max($ellipseHeight,$height-$ellipseHeight));
			imageellipse($img,$ellipsePosX,$ellipsePosY,$ellipseWidth,$ellipseHeight,$elcolor);
			imagefill($img,$ellipsePosX,$ellipsePosY,$elcolor);
		}
		//dots
		for ($i=0;$i<$width*$height/12;++$i)
		{
			$x=jf::rand(0,$width);
			$y=jf::rand(0,$height);
			imagesetpixel($img,$x,$y,$color);
		}
		//dots
		for ($i=0;$i<$width/10;++$i)
		{
			$x1=jf::rand(0,$width);
			$x2=jf::rand(0,$width);
			$y1=jf::rand(0,$width);
			$y2=jf::rand(0,$width);
			imageline($img,$x1,$y1,$x2,$y2,$color);
		}
		//arcs
		for ($i=0;$i<$width/4;++$i)
		{
			$cx=jf::rand(0,$width);
			$cy=jf::rand(0,$height);
			$start=jf::rand(0,180);
			$end=jf::rand(0,180);
			$awidth=jf::rand(0,max(min($cx,$width-$cx),10));
			$aheight=jf::rand(0,max(min($cy,$height-$cy),10));
			imagearc($img,$cx,$cy,$awidth,$aheight,$start,$end,$color);
		}
		
		//text
		for ($i=0;$i<count($text);++$i)
		{
			imagettftext($img,$fontSize,mt_rand(-5,5),10+($fontSize-8)*$sumWidth+20*$i,$fontSize+mt_rand(-10,10)+10,$color,$font,$text[$i]);
			$sumWidth+=strlen($text[$i]);
		}
		
		if (imageistruecolor($img))
		{
			header ( "content-type: image/png" );
			echo imagepng ( $img );
		}
		exit ();
	}
	function Present()
	{
		$this->DumpLabel();
		echo "<input type='text' ";
		$this->DumpAttributes();
		echo " /> <img height='40' src='".HttpRequest::Path()."?CAPTCHA=".urlencode($this->Name())."' border='1px' onclick=\"this.src=''; this.src='".HttpRequest::Path()."?CAPTCHA=".urlencode($this->Name())."';\"  style='cursor:pointer;vertical-align:middle;' title='Click for a new image if you cant read this one.' />";
	}
}