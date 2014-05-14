<?php

class DoctrinePlugin extends JPlugin {
	static protected $tablePrefix;
	static protected $classLoader;
	static protected $cache;
	static protected $config;
	static public $eventManager;
	/**
	 *	@var Doctrine\ORM\EntityManager
	 *
	 */
	static public $entityManager;
	public static function AutoloadSetup() {
		require __DIR__ . '/doctrine/Doctrine/Common/ClassLoader.php';
		self::$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', __DIR__ . "/doctrine");
		self::$classLoader->register(); // register on SPL autoload stack

	}
	public static function Config($prefix = "app_") {
		self::$config = new Doctrine\ORM\Configuration(); // (2)

		// Proxy Configuration (3)
		self::$config->setProxyDir(__DIR__ . "/doctrine/proxy_cache");
		self::$config->setProxyNamespace(jf_Application_Name . '\Proxies');
		self::$config->setAutoGenerateProxyClasses(jf::$RunMode->IsDevelop());

		// Mapping Configuration (4)
		//$driverImpl = new Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__."/entities/xml");
		//$driverImpl = new Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__."/config/mappings/yml");
		$driverImpl = self::$config->newDefaultAnnotationDriver(__DIR__ . "/../model");
		self::$config->setMetadataDriverImpl($driverImpl);

		// Caching Configuration (5)
		if (jf::$RunMode->IsDeploy() and function_exists("apc_exists")) {
			self::$cache = new \Doctrine\Common\Cache\ApcCache();
		} else {
			self::$cache = new \Doctrine\Common\Cache\ArrayCache();
		}
		self::$config->setMetadataCacheImpl(self::$cache);
		self::$config->setQueryCacheImpl(self::$cache);

		// database configuration parameters (6)
		$db = \jf\DatabaseManager::Configuration();
		$adapter = $db->Adapter;
		if ($adapter == "mysql" or $adapter == "mysqli" or !$adapter)
			$adapter = "pdo_mysql";
		$conn = array('driver' => $adapter, 'user' => $db->Username, 'password' => $db->Password, 'host' => $db->Host, 'dbname' => $db->DatabaseName, 'charset' => 'utf8', 'path' => $db->DatabaseName,);

		require_once(__DIR__ . "/doctrine/sqllogger.php");
		self::$config->setSQLLogger(new Doctrine\DBAL\Logging\jframeworkSQLLogger());

		// obtaining the entity manager (7)
		self::$eventManager = new Doctrine\Common\EventManager();

		self::$tablePrefix = new \Doctrine\Extensions\TablePrefix($prefix);
		self::$eventManager->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, self::$tablePrefix);
		self::$entityManager = \Doctrine\ORM\EntityManager::create($conn, self::$config, self::$eventManager);
	}

	public static function CreateSchema() {
		$metadata = self::$entityManager->getMetadataFactory()->getAllMetadata();
		$schemaTool = new Doctrine\ORM\Tools\SchemaTool(self::$entityManager);
		$schemaTool->createSchema($metadata);
		return;
	}
	public static function UpdateSchema() {
		$metadata = self::$entityManager->getMetadataFactory()->getAllMetadata();
		$schemaTool = new Doctrine\ORM\Tools\SchemaTool(self::$entityManager);
		$schemaTool->updateSchema($metadata, true);
		return;
	}

	public static function Load($prefix = "app_") {
		register_shutdown_function("DoctrinePlugin::Shutdown");
		self::AutoloadSetup();
		self::Config($prefix);

		if (jf::$RunMode->IsDevelop())
			$r = self::UpdateSchema();

		require_once(__DIR__ . "/doctrine/helper.php");
	}

	public static function Shutdown() {
		self::$entityManager->flush();
	}

}
