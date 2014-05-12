<?php

/**
 * Handy captcha class for jFramework
 * @author abiusx
 * @version 1.3
 */
class CaptchaPlugin extends BasePluginClass
{
	function Present($Title="temp")
	{
		$Text=$this->GenerateText();
		j::SaveSession("CAPTCHA_{$Title}",$Text,60*10);
		$this->Draw($Text);
	}
	function Check($Value,$Title="temp")
	{
		$X=j::LoadSession("CAPTCHA_{$Title}");
		j::DeleteSession("CAPTCHA_{$Title}");
		return $X==$Value;
	}
	static function CheckInput($Value,$Title="temp")
	{
		$X=j::LoadSession("CAPTCHA_{$Title}");
		j::DeleteSession("CAPTCHA_{$Title}");
		return $X===$Value;
	}
	
	function GenerateText()
	{
		$list="abcdefghijkmnopqrstuvwxyz";
		$text=array();
		$words=mt_rand(1,2);
		for ($i=0;$i<$words;++$i)
		{
			$length=mt_rand(2,5);
			$word="";
			for ($j=0;$j<$length;++$j)
				$word.=$list[mt_rand(0,strlen($list)-1)];
			$text[]=$word;
		}
		return implode(" ",$text);
			
	}
	function Draw($text)
	{
		$font=dirname(__FILE__)."/captcha/font.ttf";
		$fontSize=20;
		$width=($fontSize-5)*strlen($text)+40;
		$height=$fontSize+30;
		$img=imagecreatetruecolor($width,$height);
		imagealphablending($img, true); 
		imagesavealpha($img, true);
		$r=mt_rand(20,235);
		$b=mt_rand(20,235);
		$g=mt_rand(20,235);
		$bgcolor=imagecolorallocatealpha($img,$r,$g,$b,0);
		imagefill($img,1,1,$bgcolor);
		$ex=mt_rand(15,20);
		if (mt_rand(0,1)) $ex=-$ex;
		$b+=$ex;
		$r+=$ex;
		$g+=$ex;
		$color=imagecolorallocate($img,$r,$b,$g);
		$text=explode(" ",$text);
		$sumWidth=0;

		$r-=2*ex;
		$b-=2*ex;
		$g-=2*ex;
		$elcolor=imagecolorallocatealpha($img,$r,$g,$b,20);
		
		$ellipseWidth=mt_rand(30,$width-50);
		$ellipsePosX=mt_rand($ellipseWidth,$width-$ellipseWidth);
		$ellipseHeight=mt_rand(10,40);
		$ellipsePosY=mt_rand($ellipseHeight,$height-$ellipseHeight);
		imageellipse($img,$ellipsePosX,$ellipsePosY,$ellipseWidth,$ellipseHeight,$elcolor);
		imagefill($img,20,30,$elcolor);
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
	static function InsertIMGTag($Title,$ID="CAPTCHA")
	{
		?>
<img align="middle" <?php if ($ID) echo "id='{$ID}' ";?> src='/user/captcha?title=<?php echo $Title;?>&random=<?php echo rand(0,1000000);?>' border='1'/>
		<?php
	}
	static function InsertReloadScript($Title,$ID="CAPTCHA")
	{
		?>
		<script>
function reloadCaptcha(e)
{
	$("#<?php echo $ID;?>").attr("src",'<?php echo SiteRoot;?>/user/captcha?title=<?php echo $Title;?>&random='+Math.floor(Math.random()*100000));
	return false;
}
</script>
		<?php		
	}		
}