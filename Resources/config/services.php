<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use YousignBundle\Service\YousignClient;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container) {

    $services = $container->services();

    $services->set(YousignClient::class, YousignClient::class)
        ->public()
        ->autowire()
        ->autoconfigure()
        ->args(
            [
                param('yousign.baseUrl'),
                param('yousign.apiKey')
            ]
        )
    ;
};