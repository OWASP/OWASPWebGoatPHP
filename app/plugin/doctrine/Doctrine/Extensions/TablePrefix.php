<?php

namespace Doctrine\Extensions;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class TablePrefix
{
	protected $prefix = '';

	public function __construct($prefix)
	{
		$this->prefix = (string) $prefix;
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
	{
		$classMetadata = $eventArgs->getClassMetadata();
		if (substr($classMetadata->getTableName(),0,2)!="jf" and substr($classMetadata->getTableName(),0,4)!="app_")
		$classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
		foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
			if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
				$mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
				if (substr($classMetadata->getTableName(),0,2)!="jf" and substr($classMetadata->getTableName(),0,4)!="app_")
				$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
			}
		}
	}

}