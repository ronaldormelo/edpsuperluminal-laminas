<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CacheCodeGeneratorFactory
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheCodeGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        /** @var ClassDeclarationService $classDeclarationService */
        $classDeclarationService = $serviceLocator->get('EdpSuperluminal\ClassDeclarationService');

        return new CacheCodeGenerator(
            $useStatementService,
            $classDeclarationService
        );
    }
}