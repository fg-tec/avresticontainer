<?php

/**
 * Index file to demonstrate the usage of the container.
 *
 * @package Example
 */

require __DIR__ . '/../vendor/autoload.php';

use Avresticontainer\Container;
use Example\Classes\Config;
use Example\Classes\DatabaseConnection;
use Example\Classes\Service;
use Example\Classes\AppService;

// Create and configure the container
$container = Container::getInstance();

// Bindings
$container->singleton(Config::class, function() {
    return new Config([
        'db_host' => '127.0.0.1',
        'db_name' => 'my_database'
    ]);
});

$container->bind(DatabaseConnection::class);
$container->bind(Service::class);
$container->bind(AppService::class);

// Resolving a service
/** @var Service $service */
$service = $container->make(Service::class);
echo $service->performTask();

// Resolving a singleton
/** @var AppService $appService */
$appService = $container->make(AppService::class);
echo $appService->run();
