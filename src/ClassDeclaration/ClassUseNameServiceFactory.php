<?php

namespace EdpSuperluminal\ClassDeclaration;


use Laminas\ServiceManager\ServiceLocatorInterface;

class ClassUseNameServiceFactory
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        return new ClassUseNameService($useStatementService);
    }
}