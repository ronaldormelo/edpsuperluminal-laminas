<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

class IsZf2BasedAutoloader implements SpecificationInterface
{

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
        return in_array('Laminas\Loader\SplAutoloader', $class->getInterfaceNames());
    }
}