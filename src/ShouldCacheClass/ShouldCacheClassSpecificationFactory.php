<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\ServiceManager\ServiceLocatorInterface;

class ShouldCacheClassSpecificationFactory
{
    protected $specificationClasses = array(
        'IsNonZendClass',
        'IsZendAutoloader',
        'IsAnAnnotatedClass',
        'IsZf2BasedAutoloader',
        'IsCoreClass',
        'HasMagicDir'
    );

    public function __construct($specificationClasses = null)
    {
        if (!is_null($specificationClasses)) {
            $this->specificationClasses = $specificationClasses;
        }
    }


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws \Exception
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $specifications = array();

        foreach ($this->specificationClasses as $specificationClass) {
            $specificationClass = 'EdpSuperluminal\ShouldCacheClass\\' . $specificationClass;

            if (!class_exists($specificationClass)) {
                throw new \Exception("The specification '{$specificationClass}' does not exist!");
            }

            $specification = new $specificationClass();

            if (!$specification instanceof SpecificationInterface) {
                throw new \Exception("The specifications provided must implement SpecificationInterface!");
            }

            $specifications[] = $specification;
        }

        return new ShouldCacheClassSpecification($specifications);
    }
}