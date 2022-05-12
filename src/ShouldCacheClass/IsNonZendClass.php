<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

class IsNonZendClass implements SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {

        $defaultIncluded = array(
            'Laminas',
            'Psr',
            'Interop',
        );

        $defaultExcluded = array(
            'Laminas\Stdlib\Glob',
            'zend\stdlib\glob',
            'Laminas\Db\Adapter\Driver\Pdo',
//            'Doctrine\\',
//            'Gedmo\\',
        );

        foreach ($defaultIncluded as $includedClass) {
            $isNotIncluded = (strpos($class->getName(), $includedClass) === false);
            if (!$isNotIncluded) {
                break;
            }
        }

        // check for classes which are *specifically* excluded - e.g. Zend classes which use __DIR__
        foreach ($defaultExcluded as $excludedClass) {
            $isExcluded = (strpos($class->getName(), $excludedClass) !== false);
            if ($isExcluded) {
                $isNotIncluded = true;
                break;
            }
        }

        return $isNotIncluded;
    }
}
