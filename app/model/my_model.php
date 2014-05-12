<?php

class My_model extends JModel
{

	public function test()
	{
		jf::SQL("INSERT into test values('Wooah', 'Model', 987654321)");
	}
}

?>