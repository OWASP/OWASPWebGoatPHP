<?php
class LibAdapterMainTest extends JTestSuite
{
	function __construct()
	{
		$userConfig= \jf\DatabaseManager::Configuration();
		switch ($userConfig->Adapter)
		{
			case "mysqli":
			case "pdo_mysql":
			case "mariaDB":
				$this->add("jf/test/lib/db/adapter/pdo_mysql");
				$this->add("jf/test/lib/db/adapter/mysqli");
				$this->add("jf/test/lib/db/adapter/mariaDB");
				break;
			case "pdo_sqlite":
				$this->add("jf/test/lib/db/adapter/pdo_mysql");
				break;
		}
	}
}