<?php

use PHPUnit\Framework\TestCase;
use Avresticontainer\Container;
use Example\Classes\Config;
use Example\Classes\DatabaseConnection;

class ContainerAddDefinitionsTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testAddDefinitions()
    {
        $container = Container::getInstance();

        // Add definitions
        $container->addDefinitions([
            'config' => function() {
                return new Config(['db_host' => '127.0.0.1', 'db_name' => 'my_database']);
            },
            'database' => function($container) {
                return new DatabaseConnection($container->make('config'));
            },
            'simple.value' => 'This is a simple value'
        ]);

        // Resolve 'config'
        $config = $container->make('config');
        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals('127.0.0.1', $config->get('db_host'));

        // Resolve 'database'
        $database = $container->make('database');
        $this->assertInstanceOf(DatabaseConnection::class, $database);
        $this->assertEquals('Connected to database \'my_database\' at \'127.0.0.1\'<br>', $database->connect());

        // Resolve 'simple.value'
        $simpleValue = $container->make('simple.value');
        $this->assertEquals('This is a simple value', $simpleValue);
    }
}
