<?php
class MockTest {
	function Mockery()
	{
		return "1234";
	}
}
Mock::generate("MockTest");
class MainTest extends JTest 
{
	protected $mockObj;
	function setUp()
	{
		$this->mockObj=new MockMockTest($this);
		$this->mockObj->setReturnValue("Mockery","hello");
	}
	function tearDown()
	{
		
	}
	function testSomething()
	{
        $this->assertTrue(1==1);
        $this->assertFalse(1==2);
	}
	function testSomethingElse()
	{
		$this->assertTrue($this->mockObj->Mockery()=="hello");
	}
	function testError()
	{
		$R=j::SQL("SELECT * FROM ordu_jihad");
		?>
		<table dir='rtl' width='100%' border='1'>
		<thead>
		<tr>
		<?php
		foreach ($R[0] as $k=>$h)
		{
			echo "<th>{$k}</th>\n";
		}
		?>
		</tr>
		</thead>
		
				
		<?php
		foreach ($R as $r)
		{
			?>
			<tr align='center'>
			<?php			
			foreach ($r as $x)
			{
				echo "<td>{$x}</td>\n";	
			}
			?>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
}

