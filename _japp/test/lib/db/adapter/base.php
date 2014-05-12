<?php
abstract class LibDbBaseTest extends JDbTest
{
	function TablePrefix()
	{
		return \jf\DatabaseManager::Configuration()->TablePrefix;
	}
	function testLastID()
	{
		$insDb=jf::db();
		jf::SQL("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);",
		"username1","some_pass", "some_salt", 1);
		$first=$insDb->LastID();
		jf::SQL("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);",
		"username2","another_pass", "another_salt", 1);
		$second=$insDb->LastID();
		$this->assertEquals($second,$first+1);
	}
	abstract function testQuote();
	function testQuery()
	{
		$insDb=jf::db();
		$this->assertNotNull($insDb->query("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES('username1','some_pass', 'some_salt', 1)"));
	}
	function testPrepare()
	{
		$insDb=jf::db();
		$r=$insDb->prepare("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);");
		$this->assertInstanceOf("\\jf\\BaseDatabaseStatement", $r);
	
		try
		{
			$r=$insDb->prepare("INSERT INTO {$this->TablePrefix()}users (Username, Pasword, Salt, Protocol) VALUES (?,?,?,?);");
			$this->assertInstanceOf("\\jf\\BaseDatabaseStatement", $r);
			$this->fail();
		} catch(Exception $e) { }
	}
	function testExec()
	{
		$insDb=jf::db();
		for ($i=0; $i<2; $i++)
			jf::SQL("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);",
				"username{$i}","some_pass{$i}", "some_salt", 1);
		$r=$insDb->exec("UPDATE {$this->TablePrefix()}users SET Protocol=10 WHERE Salt='some_salt';");
		$this->assertEquals(2, $r);
	
		$r=$insDb->exec("UPDATE {$this->TablePrefix()}users SET Protocol=9 WHERE Salt='another_salt';");
		$this->assertEquals(0, $r);
	}
	/**
	* @depends testExec
	*/
	function testInitialize()
	{
		$insDb=jf::db();
		$config= \jf\DatabaseManager::Configuration();
	
		$tableList=$insDb->ListTables($config->DatabaseName);
		$insDb->Initialize($config->DatabaseName);
		foreach($tableList as $table)
			$this->assertLessThanOrEqual(jf::SQL("SELECT * FROM {$table};"),0);
	}
	function testInitializeData()
	{
		$insDb=jf::db();
		$config= \jf\DatabaseManager::Configuration();
	
		$insDb->InitializeData($config->DatabaseName);
		$r=jf::SQL("SELECT count(*) AS Num FROM {$this->TablePrefix()}users");
		$this->assertLessThan($r[0]['Num'],0);
	}
}

abstract class LibDbStatementBaseTest extends JDbTest
{
	function TablePrefix()
	{
		return \jf\DatabaseManager::Configuration()->TablePrefix;
	}
	function testExecute()
	{
		$insDb=jf::db();
	
		$r=jf::SQL("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES('username1','some_pass', 'some_salt', 1)");
		jf::SQL("DELETE FROM {$this->TablePrefix()}users WHERE Username='username1'");
		$myStatement=$insDb->prepare("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?)");
		$myStatement->execute("username1","some_pass", "some_salt",1);
		$this->assertEquals($r+1, $insDb->LastID());
	}
	/**
	 * @depends testExecute
	 */
	function testBindAll()
	{
		$insDb=jf::db();
		$myStatement=$insDb->prepare("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);");
	
		$myStatement->bindAll("username1","some_pass", "some_salt", 1);
		$this->assertEquals(1, $myStatement->execute());
	}
	function testRowCount()
	{
		$insDb=jf::db();
	
		$myStatement=$insDb->prepare("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES (?,?,?,?);");
		$r=$myStatement->execute("username1","some_pass", "some_salt",1);
		$this->assertEquals($r, $myStatement->rowCount());
	}
	function testFetchAll()
	{
		$insDb=jf::db();
		$insDb->exec("INSERT INTO {$this->TablePrefix()}users (Username, Password, Salt, Protocol) VALUES('username1','some_pass', 'some_salt', 1);");
	
		$r=jf::SQL("SELECT * FROM {$this->TablePrefix()}users;");
		$myStatement=$insDb->prepare("SELECT * FROM {$this->TablePrefix()}users;");
		$myStatement->execute();
		$this->assertEquals($r, $myStatement->fetchAll());
	}
	function testFetch()
	{
		$insDb=jf::db();
	
		$r=jf::SQL("SELECT * FROM {$this->TablePrefix()}users;");
		$myStatement=$insDb->prepare("SELECT * FROM {$this->TablePrefix()}users;");
		$myStatement->execute();
	
		$this->assertEquals($r[0], $myStatement->fetch());
	}
}