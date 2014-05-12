<?php
class LibProfilerTest extends JTest
{
	function testGetTime()
	{
 		$profiler=new \jf\Profiler();
 	
 		$profileTime=(int)$profiler->GetTime(false);		
		$jfTime=jf::time();
		$this->assertTrue($profileTime==$jfTime or $profileTime==$jfTime-1);
		
		$profileTime=$profiler->GetTime(false);
		$profileTime*=1000000;
		$time=$profiler->GetTime(true);
 		$this->assertGreaterThan($profileTime-$time,100);
	}
	function testTimer()
	{
		$profiler=new \jf\Profiler();
		$this->assertEquals($profiler->Timer(4000000,10000000),6.0);
		
		$profiler->Reset();
		usleep(100);
		$profiler->Stop();
		$this->assertGreaterThan($profiler->Timer()-0.0001,0.001);
		
	}
}