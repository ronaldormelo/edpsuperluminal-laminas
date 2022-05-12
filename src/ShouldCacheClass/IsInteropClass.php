<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

class IsInteropClass implements SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
    	// var_Dump(strpos('Interop\Container\Exception\ContainerException', 'Interop'));
    	// die('IsInteropClass');
        return false;// 0 !== strpos($class->getName(), 'Interop');
    }
}