<?php

namespace EdpSuperluminal\ClassDeclaration;

use Laminas\Code\Reflection\ClassReflection;

class ClassTypeService
{
    /**
     * Determine the class type (abstract, final, interface, class)
     *
     * @param ClassReflection $reflection
     * @return string
     */
    public function getClassType(ClassReflection $reflection)
    {
        $classType = '';

        if ($reflection->isAbstract() && !$reflection->isInterface()) {
            $classType .= 'abstract ';
        }

        if ($reflection->isFinal()) {
            $classType .= 'final ';
        }

        if ($reflection->isInterface()) {
            $classType .= 'interface ';
        }

        if ($reflection->isTrait()) {
            $classType .= 'trait ';
        }

        if (!$reflection->isInterface() && !$reflection->isTrait()) {
            $classType .= 'class ';
        }

        return $classType;
    }
}
