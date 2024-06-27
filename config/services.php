<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Theod02\ApiPlatformFilterMapQueryString\ApiPlatform\FilterHandlerResourceMetadataFactory;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * @link https://symfony.com/doc/current/bundles/best_practices.html#services
 */
return static function (ContainerConfigurator $container): void {
    $container
        ->parameters()
        // ->set('theod02_api.param_name', 'param_value');
    ;
    $container
        ->services()
        ->load('Theod02\\ApiPlatformFilterMapQueryString\\', '../src/')
        ->exclude('../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}')
        // ->set('theod02_api.service_name', 'service_class')
    ;

    $container
        ->services()
        ->set(FilterHandlerResourceMetadataFactory::class)
        ->decorate(id: 'api_platform.metadata.resource.metadata_collection_factory', priority: -200)
        ->args([
            service(serviceId: sprintf('%s.inner', FilterHandlerResourceMetadataFactory::class))
        ]);
};
