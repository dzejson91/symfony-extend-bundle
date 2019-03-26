<?php
/**
 * @author Krystian Jasnos <dzejson91@gmail.com>
 */

namespace JasonMx\ExtendBundle\EventListener;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class TablePrefixListener
{
    protected $prefix = '';

    public function __construct($prefix = '')
    {
        $this->prefix = (string)$prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping)
        {
            if($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY
                && array_key_exists('name', $classMetadata->associationMappings[$fieldName]['joinTable'])
                && $mapping['sourceEntity'] == $classMetadata->getName()
            ){
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }

        if ($classMetadata->isInheritanceTypeSingleTable() && !$classMetadata->isRootEntity()) {
            return;
        }

        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
    }
}