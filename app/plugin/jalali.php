<?php

/**
 * GregorianToJalali & JalaliToGregorian Converter
 * GregorianToJalali Function source : http://www.farsiweb.info/jalali/jalali.phps
 *
 *
 *	$test = new Converter;
 *		$g2j = $test->GregorianToJalali('2000','10','10');
 *			echo $g2j[0]." ".$g2j[1]." ".$g2j[2];
 *		$j2g = $test->JalaliToGregorian('1386','12','26');
 *			echo $j2g[0]." ".$j2g[1]." ".$j2g[2];
 *
 */
class Jalali extends JPlugin
{
	protected static $GMTdelta=12600; //3:30+ GMT Tehran;
	private static function DateArray($Timestamp=null)
	{
		if ($Timestamp===null) $Timestamp=jf::time();
		$arr=self::GregorianToJalali(date("Y",$Timestamp),date("m",$Timestamp),datE("d",$Timestamp));
		return $arr;		
	}
	static function Year($Timestamp=null)
	{
		$t=self::DateArray($Timestamp);
		return $t[0];
	}
	static function Month($Timestamp=null)
	{
		$t=self::DateArray($Timestamp);
		return $t[1];
	}
	static function Day($Timestamp=null)
	{
		$t=self::DateArray($Timestamp);
		return $t[2];
	}
	/**
	 * Calculate the difference between system time and GMT +3:30 time
	 * @return number
	 */
	protected static function TimeDifference()
	{
		$ts=0;
		$ts -= date ( "Z" ) * 1;
		$daylight = date ( "I" );
		$daylight *= 1;
		$daylight *= 3600;
		$ts += $daylight; //12600 seconds 3:30+ GMT Tehran
		$ts += self::$GMTdelta;
		return $ts;
		
	}
	private static function TimeArray($Timestamp=null)
	{
		if ($Timestamp===null) $Timestamp=jf::time();
		$ts = $Timestamp+self::TimeDifference(); //strtotime(date("H:i:s"));
		return array(date ( "H", $ts ),date("i",$ts),date("s",$ts));
	} 
	static function Hour($Timestamp=null)
	{
		$t=self::TimeArray($Timestamp);
		return $t[0];
	}
	static function Minute($Timestamp=null)
	{
		$t=self::TimeArray($Timestamp);
		return $t[1];
	}
	static function Second($Timestamp=null)
	{
		$t=self::TimeArray($Timestamp);
		return $t[2];
	}
	static function DateString($Timestamp=null,$Delimiter="-")
	{
		return self::Year($Timestamp).$Delimiter.self::Month($Timestamp).$Delimiter.self::Day($Timestamp);
	}
	static function TimeString($Timestamp=null,$Delimiter=":")
	{
		return self::Hour($Timestamp).$Delimiter.self::Minute($Timestamp).$Delimiter.self::Second($Timestamp);
	}
	/**
	 * Converts a Jalali date-time string or a triple of year/month/day and a triple of Hour/Minute/Second into timestamp.
	 * @param integer|string $Year or date string
	 * @param integer $Month optional
	 * @param integer $Day optional
	 * @param integer $Hour optional
	 * @param integer $Minute optional
	 * @param integer $Second optional
	 */
	static function ToTimestamp($Year,$Month=null,$Day=null,$Hour=0,$Minute=0,$Second=0)
	{
		if ($Month===null)
		{
			$temp=explode(" ",$Year);
			$Date=$temp[0];
			$Time=$temp[1];
			$temp=explode("-",$Date);
			$Year=$temp[0];
			$Month=$temp[1];
			$Day=$temp[2];
			$temp=explode(":",$Time);
			$Hour=$temp[0];
			$Minute=$temp[1];
			$Second=$temp[2];
		}
		$arr=self::JalaliToGregorian($Year, $Month, $Day);
		$DateTimestamp=strtotime(implode("-",$arr));
		#FIXME:
		$TimeDef=0;
		if ($Hour!=0)
			$TimeDef=77400+1800;
		if ($Minute!=0)
			$TimeDef=77400;
		$TimeTimestamp=$Hour*3600+$Minute*60+$Second;
		return $DateTimestamp+$TimeTimestamp+$TimeDef;
	}
	
	private $Timestamp;
	function __construct($Timestamp=null)
	{
		if ($Timestamp===null)
			$Timestamp=jf::time();
		$this->Timestamp=$Timestamp;
		
	}
	function __toString()
	{
		return self::DateString($this->Timestamp)." ".self::TimeString($this->Timestamp);
	}
	
	
	
	
	private static $g_days_in_month = array (
		31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 
	);
	private static $j_days_in_month = array (
		31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 
	);

	
	private static function GregorianToJalali($g_y, $g_m, $g_d)
	{
		$g_days_in_month = self::$g_days_in_month;
		$j_days_in_month = self::$j_days_in_month;
		
		$gy = $g_y - 1600;
		$gm = $g_m - 1;
		$gd = $g_d - 1;
		
		$g_day_no = 365 * $gy + floor (( $gy + 3)/ 4 ) - floor( ($gy + 99)/ 100 ) + floor ( ($gy + 399)/ 400 );
		
		for($i = 0; $i < $gm; ++ $i)
			$g_day_no += $g_days_in_month [$i];
		if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
      /* leap and after Feb */
      ++ $g_day_no;
		$g_day_no += $gd;
		
		$j_day_no = $g_day_no - 79;
		
		$j_np = floor ( $j_day_no/ 12053 );
		$j_day_no %= 12053;
		
		$jy = 979 + 33 * $j_np + 4 * floor ( $j_day_no/ 1461 );
		
		$j_day_no %= 1461;
		
		if ($j_day_no >= 366)
		{
			$jy += floor ( ($j_day_no - 1)/ 365 );
			$j_day_no = ($j_day_no - 1) % 365;
		}
		
		for($i = 0; $i < 11 && $j_day_no >= $j_days_in_month [$i]; ++ $i)
		{
			$j_day_no -= $j_days_in_month [$i];
		}
		$jm = $i + 1;
		$jd = $j_day_no + 1;
		return array (
			$jy, $jm, $jd 
		);
	}

	private static function JalaliToGregorian($year, $month, $day)
	{
		$gDaysInMonth = self::$g_days_in_month;
		$jDaysInMonth = self::$j_days_in_month;
		$jy = $year - 979;
		$jm = $month - 1;
		$jd = $day - 1;
		$jDayNo = 365 * $jy + floor ( $jy/ 33 ) * 8 + floor ( (($jy % 33) + 3)/ 4 );
		for($i = 0; $i < $jm; ++ $i)
			$jDayNo += $jDaysInMonth [$i];
		$jDayNo += $jd;
		$gDayNo = $jDayNo + 79;
		//146097=365*400 +400/4 - 400/100 +400/400
		$gy = 1600 + 400 * floor ( $gDayNo/ 146097 );
		$gDayNo = $gDayNo % 146097;
		$leap = 1;
		if ($gDayNo >= 36525)
		{
			$gDayNo = $gDayNo - 1;
			//36524 = 365*100 + 100/4 - 100/100
			$gy += 100 * floor ( $gDayNo/ 36524 );
			$gDayNo = $gDayNo % 36524;
			
			if ($gDayNo >= 365)
				$gDayNo = $gDayNo + 1;
			else
				$leap = 0;
		}
		//1461 = 365*4 + 4/4
		$gy += 4 * floor ( $gDayNo/ 1461 );
		$gDayNo %= 1461;
		if ($gDayNo >= 366)
		{
			$leap = 0;
			$gDayNo = $gDayNo - 1;
			$gy += floor ( $gDayNo/ 365 );
			$gDayNo = $gDayNo % 365;
		}
		$i = 0;
		$tmp = 0;
		while ( $gDayNo >= ($gDaysInMonth [$i] + $tmp) )
		{
			if ($i == 1 && $leap == 1)
				$tmp = 1;
			else
				$tmp = 0;
			
			$gDayNo -= $gDaysInMonth [$i] + $tmp;
			$i = $i + 1;
		}
		$gm = $i + 1;
		$gd = $gDayNo + 1;
		return array (
			$gy, $gm, $gd 
		);
	}

	private function div($a, $b)
	{
		return ( int ) ($a / $b);
	}

} 


class JalaliPlugin extends Jalali
{

}
?>