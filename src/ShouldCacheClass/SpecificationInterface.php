<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

/**
 * This interface is covered by ShouldCacheClassSpecificationTest
 *
 * @codeCoverageIgnore
 */
interface SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class);
}