<?php
#
# this serves as a means of defining classes in public namespace
#
abstract class JControl extends \jf\Controller {}
abstract class JCatchControl extends \jf\CatchController {}
abstract class JController extends JControl {}
abstract class JCatchController extends JCatchControl {}

class JModel extends \jf\Model {}
class JView extends \jf\View {}
class JPlugin extends \jf\Plugin {}

//class JService extends \jf\Service {}

class HttpRequest extends \jf\HttpRequest {}

/**
 * Holds the modes of the application
 * You mostly need to use convenient IsFoo methods of this class
 * @author abiusx
 *
 */
class RunModes
{
	/**
	 * Development mode flag
	 * @var integer
	 */
	const Develop=1;

	/**
	 * Deployment mode flag
	 * @var integer
	 */
	const Deploy=2;
	/**
	 * Command line mode flag
	 * @var integer
	 */
	const CLI=4;
	/**
	 * Embedded mode flag
	 * @var integer
	 */
	const Embed=8;

	/**
	 * Holds the mixture of modes
	 * @var integer
	 */
	protected $Mode=0;

	/**
	 * Adds a mode to current running modes
	 * @param integer $Mode one of the RunModes constants
	 */
	function Add($Mode)
	{
		$this->Mode= $this->Mode | $Mode;
	}
	/**
	 * Sets this mode as all the running modes
	 * @param integer $Mode one of the RunModes constants
	 */
	function Set($Mode)
	{
		$this->Mode=$Mode;
	}
	/**
	 * Checks whether the mode is active
	 * @param integer $Mode one of the RunModes constants
	 */
	function Is($Mode)
	{
		return $this->Mode & $Mode;

	}
	/**
	 * Removes a mode from current modes
	 * @param integer $Mode one of the RunModes constants
	 */
	function Remove($Mode)
	{
		$this->Mode=$this->Mode & (~$Mode);
	}
	/**
	 * Toggles a running mode on or off
	 * @param integer $Mode one of the RunModes constants
	 */
	function Toggle($Mode)
	{
		$this->Mode =$this->Mode ^ $Mode;
	}


	function IsEmbed()
	{
		return $this->Is(RunModes::Embed);
	}
	function IsCLI()
	{
		return $this->Is(RunModes::CLI);
	}
	function IsDeploy()
	{
		return $this->Is(RunModes::Deploy);
	}
	function IsDevelop()
	{
		return $this->Is(RunModes::Develop);
	}
}
jf::$RunMode=new RunModes();


define("TIMESTAMP_MINUTE",60);
define("TIMESTAMP_HOUR",TIMESTAMP_MINUTE*60);
define("TIMESTAMP_DAY",TIMESTAMP_HOUR*24);
define("TIMESTAMP_WEEK",TIMESTAMP_DAY*7);
define("TIMESTAMP_MONTH",TIMESTAMP_DAY*30);
define("TIMESTAMP_YEAR",TIMESTAMP_DAY*365);
define("TIMESTAMP_FOREVER",TIMESTAMP_YEAR*128);