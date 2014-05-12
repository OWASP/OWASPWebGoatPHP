<?php

class Hello extends JModel
{

	public function test()
	{
		$f_name = "WOaah";
		$l_name = "Model";
		$id = 123;
		jf::SQL("INSERT into test values(?, ?, ?)", $f_name, $l_name, $id);

	}
}

?>