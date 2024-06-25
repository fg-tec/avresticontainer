<?php

use PHPUnit\Framework\TestCase;
use Avresticontainer\Container;

class SingletonTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testSingleton()
    {
        $container = Container::getInstance();
        $container->singleton('config', function() {
            return new class {
                public int $value = 0;
            };
        });

        $config1 = $container->make('config');
        $config2 = $container->make('config');
        $config1->value = 5;

        $this->assertEquals(5, $config2->value);
    }
}
