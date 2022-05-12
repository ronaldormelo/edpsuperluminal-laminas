<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

class IsZendAutoloader implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        $className = $class->getName();

        return $className === 'Laminas\Loader\AutoloaderFactory'
            || $className === 'Laminas\Loader\SplAutoloader';
    }
}