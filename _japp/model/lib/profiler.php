<?php
namespace jf;
class Profiler extends Model 
{
    function __construct ()
    {
        $this->Reset();
    }
    
    private $Start=null;
    private $End=null;
    private $TimeMicroseconds;
    private $Time;
    
    /**
     * Returns time in microseconds
     * @param boolean $ReturnMicroseconds
     * @return float
     */
    function GetTime ($ReturnMicroseconds=true)
    {
        list($microsec,$sec)=explode(" ",microtime());
        $utime = $microsec +$sec;
        if ($ReturnMicroseconds) {
            $utime *= 1000000;
        }
        return $utime;
    }

    /**
     * Reset the timer
     * @return float
     */
    function Reset ()
    {
        return $this->Start = $this->GetTime(true);
    }
    /**
     * Returns the time calculated
     * @param int $Start
     * @param int $End
     * @return float
     */
    function Timer ($Start=null,$End=null)
    {
        if ($Start===null) $Start=$this->Start;
        if ($End===null) 
        	if ($this->End===null)
        		$End=$this->GetTime(true);
        	else
        		$End=$this->End;
        $this->TimeMicroseconds = $End - $Start;
        return $this->Time = $this->TimeMicroseconds / 1000000.0;
    }
    
    /**
     * Stops the timer
     * @return float
     */
    function Stop()
    {
    	return $this->End=$this->GetTime(true);
    }
}