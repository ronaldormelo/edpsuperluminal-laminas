<?php

namespace EdpSuperluminal;

use Laminas\Console\Request as ConsoleRequest;
use Laminas\Mvc\MvcEvent;

/**
 * Create a class cache of all classes used.
 *
 * @package EdpSuperluminal
 */
class Module
{
    /**
     * Attach the cache event listener
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var CacheBuilder $cacheBuilder */
        $cacheBuilder = $serviceManager->get('EdpSuperluminal\CacheBuilder');

        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $eventManager->attach('Laminas\Mvc\Application', 'finish', function (MvcEvent $e) use ($cacheBuilder) {
            $request = $e->getRequest();

            if ($request instanceof ConsoleRequest ||
                $request->getQuery()->get('EDPSUPERLUMINAL_CACHE', null) === null) {
                return;
            }

            $cacheBuilder->cache(ZF_CLASS_CACHE);
        });
    }

    /**
     *
     */
    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'EdpSuperluminal\ClassDeclaration\UseStatementService' => 'EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService',
            ],
            'factories' => [
                'EdpSuperluminal\CacheCodeGenerator' => function ($sm) {
                    $service = new \EdpSuperluminal\CacheCodeGeneratorFactory();
                    return $service->createService($sm);
                },
                'EdpSuperluminal\CacheBuilder' => function ($sm) {

                    $service = new \EdpSuperluminal\CacheBuilderFactory();
                    return $service->createService($sm);
                },
                'EdpSuperluminal\ShouldCacheClass' => function ($sm) {
                    $service = new \EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecificationFactory();
                    return $service->createService($sm);
                },
                'EdpSuperluminal\ClassDeclarationService' => function ($sm) {
                    $service = new \EdpSuperluminal\ClassDeclaration\ClassDeclarationServiceFactory();
                    return $service->createService($sm);
                },
                'EdpSuperluminal\ClassDeclaration\ClassUseNameService' => function ($sm) {
                    $service = new \EdpSuperluminal\ClassDeclaration\ClassUseNameServiceFactory();
                    return $service->createService($sm);
                },
            ],
        ];
    }
}
