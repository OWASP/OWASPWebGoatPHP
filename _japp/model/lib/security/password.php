<?php
namespace jf;

/**
 * BasePassword class
 * in charge of generating and determining strength of a password
 * @author abiusx
 *
 */
class BasePassword
{
	/**
	 * Computes information entropy of a string
	 * @param string $string
	 * @return float
	 */
	private static function Entropy($string)
	{
		$h=0;
		$size = strlen($string);
		foreach (count_chars($string, 1) as $v) {
			$p = $v/$size;
			$h -= $p*log($p)/log(2);
		}
		return $h;
	}
	/**
	 * detect if a string has ordered characters of some length
	 * @param string $string
	 * @param integer $length
	 * @return boolean
	 */
	private static function hasOrderedCharacters($string, $length) {
		$length=(int)$length;
		$i = 0;
		$j = strlen($string);
		$str = implode('', array_map(function($m) use (&$i, &$j) {
			return chr((ord($m[0]) + $j--) % 256) . chr((ord($m[0]) + $i++) % 256);
		}, str_split($string, 1)));
		return preg_match('#(.)(.\1){' . ($length - 1) . '}#', $str)==true;
	}
	/**
	 * Checks for patterns of keyboard keys in a string
	 * @param string $string
	 * @param length $length
	 * @return boolean
	 */
	private static function hasKeyboardOrderedCharacters($string, $length) {
		$length=(int)$length;
		$i = 0;
		$j = strlen($string);
		$str = implode('', array_map(function($m) use (&$i, &$j) {
			$keyboardSet="1234567890qwertyuiopasdfgklzxcvbnm";
			return ((strpos($keyboardSet,$m[0]) + $j--) ) . ((strpos($keyboardSet,$m[0]) + $i++) );
		}, str_split($string, 1)));
		return preg_match('#(.)(.\1){' . ($length - 1) . '}#', $str)==true;
	}
	/**
	 * Determines strength of a password
	 * Generally something above .3 is acceptable and above .5 is strong
	 * You should definitely reject strengths below .1
	 * @param string $RawPassword
	 * @return float between 0 and 1
	 */
	static function Strength($RawPassword)
	{
		$score=0;

		//initial score is the entropy of the password
		$entropy=self::Entropy($RawPassword);
		$score+=$entropy/4; //maximum entropy is 8

		//check for sequence of letters
		$ordered=self::hasOrderedCharacters($RawPassword, strlen($RawPassword)/2);
		$fullyOrdered=self::hasOrderedCharacters($RawPassword, strlen($RawPassword));
		$hasKeyboardOrder=self::hasKeyboardOrderedCharacters($RawPassword,strlen($RawPassword)/2);
		$keyboardOrdered=self::hasKeyboardOrderedCharacters($RawPassword,strlen($RawPassword));


		if ($fullyOrdered)
			$score*=.1;
		elseif ($ordered)
		$score*=.5;

		if ($keyboardOrdered)
			$score*=.15;
		elseif ($hasKeyboardOrder)
		$score*=.5;

		//check for date patterns
		preg_match_all ("/^(19\d{2})*$/i", $RawPassword, $matches);
		$isDate = count($matches[0])>=1;
		preg_match_all ("/19\d{2}/i", $RawPassword, $matches);
		$hasDate = count($matches[0])>=1;

		if ($isDate)
			$score*=.2;
		elseif ($hasDate)
		$score*=.5;

		//check for phone numbers
		preg_match_all ("/^0\d{6,11}$/i", $RawPassword, $matches);
		$isPhoneNumber = count($matches[0])>=1;
		preg_match_all ("/\d{6,11}/i", $RawPassword, $matches);
		$hasPhoneNumber = count($matches[0])>=1;

		if ($isPhoneNumber)
			$score*=.5;
		elseif ($hasPhoneNumber)
		$score*=.9;

		//check for variety of character types
		preg_match_all ("/\d/i", $RawPassword, $matches);
		$numbers = count($matches[0])>=1;

		preg_match_all ("/[a-z]/", $RawPassword, $matches);
		$lowers = count($matches[0])>=1;

		preg_match_all ("/[A-Z]/", $RawPassword, $matches);
		$uppers = count($matches[0])>=1;

		preg_match_all ("/[^A-z0-9]/", $RawPassword, $matches);
		$others = count($matches[0])>=1;

		$setMultiplier=($others+$uppers+$lowers+$numbers)/4;

		$score=$score/2 + $score/2*$setMultiplier;


		return min(1,max(0,$score));

	}
	/**
	 * Generate a secure textual password, but because of random generating it does not have exactly same security
	 * @param float $Security between 0 and 1
	 */
	static function Generate($Security=.5)
	{
		$MaxLen=20;
		if ($Security>.3)
			$UseNumbers=true;
		else
			$UseNumbers=false;
		if ($Security>.5)
			$UseUpper=true;
		else
			$UseUpper=false;
		if ($Security>.9)
			$UseSymbols=true;
		else
			$UseSymbols=false;
		$Length=max($Security*$MaxLen,4);

		$chars='abcdefghijklmnopqrstuvwxyz';
		if ($UseUpper)
			$chars.="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($UseNumbers)
			$chars.="0123456789";
		if ($UseSymbols)
			$chars.="!@#$%^&*()_+-=?.,";

		$Pass="";
		for ($i=0;$i<$Length;++$i)
			$Pass.=$chars[jf::rand(0, strlen($chars)-1)];
		return $Pass;
	}
}
/**
 * Password class
 * takes charge of creating secure passwords and validating them
 * this class creates a password object, which has a dynamic and a static salt and a hashed password from a username and raw password
 * has protocol versions to be backward compatible, i.e when a hashing mechanism is deemed insecure, the mechanism can change but
 * since we don't have access to textual passwords, new passwords can not be generated so older protocols still supported to perform login
 * and then update the password.
 * @author abiusx
 * @version 1.2
 */
class Password extends BasePassword
{

	protected $DynamicSalt=null;
	/**
	 * Static salt concatenated to dynamic salt
	 * @var string
	 * @version 1.0
	 */
	protected static $StaticSalt="7d2cdb76dcc3c97fc55bff3dafb35724031f3e4c47512d4903b6d1fb914774405e74539ea70a49fbc4b52ededb1f5dfb7eebef3bcc89e9578e449ed93cfb2103";
	/**
	 * change this everytime you change the way password is generated to update
	 * @var integer
	 */
	protected static $Protocol=1;
	protected $Username;
	protected $Password;

	/**
	 * returns the static salt of password generator
	 */
	function StaticSalt()
	{
		return self::$StaticSalt;
	}


	/**
	 * Creates a hashed password
	 * @param string $Username
	 * @param string $RawPassword
	 * @param string $DynamicSalt
	 * @param integer $Protocol
	 */
	public function Make($Username,$RawPassword,$DynamicSalt=null,$Protocol=null)
	{
		if ($DynamicSalt===null)
			$this->DynamicSalt=hash("sha512",jf::rand());
		else
			$this->DynamicSalt=$DynamicSalt;
		if ($Protocol===null)
			$Protocol=$this->Protocol();
		$this->Username=$Username;
		if ($Protocol==1)
			$this->Password=hash("sha512",strtolower($this->Username()).$RawPassword.$this->Salt().$this->StaticSalt());

	}

	/**
	 * Validates if a hashed password is the correct hashed password for a given raw password, username and salt
	 * @param string $Username
	 * @param string $RawPassword the textual password (entered by user at login)
	 * @param string $HashedPassword the hashed password (retrived from database)
	 * @param string $Salt the dynamic salt (from database)
	 * @param integer $Protocol optional for backward compatibility
	 * @return boolean
	 */
	static function Validate($Username,$RawPassword,$HashedPassword,$Salt,$Protocol=null)
	{
		$temp=new Password($Username,$RawPassword,$Protocol);
		$temp->Make($Username, $RawPassword,$Salt,$Protocol);
		return ($temp->Password()==$HashedPassword);
	}
	/**
	 * Create a new password
	 * @param string $Username
	 * @param string $RawPassword the textual password
	 * @param int $Protocol protocol of password hashing. omit if wanna use most recent.
	 */
	function __construct($Username,$RawPassword,$Protocol=null)
	{
		$this->Make($Username,$RawPassword,$Protocol);
	}

	/**
	 * Returns the dynamic salt of the password
	 * @return string
	 */
	function Salt()
	{
		return $this->DynamicSalt;
	}
	/**
	 * Returns the username
	 * @return string
	 */
	function Username()
	{
		return $this->Username;
	}
	/**
	 * Returns the hashed password
	 */
	function Password()
	{
		return $this->Password;
	}
	/**
	 * determines the hashing protocol
	 * @return string
	 */
	function Protocol()
	{
		return self::$Protocol;
	}
}