<?php

use PHPUnit\Framework\TestCase;
use Avresticontainer\Container;
use Avresticontainer\Exceptions\NotFoundException;
use Avresticontainer\Exceptions\BindingResolutionException;
use Avresticontainer\Exceptions\NonInstantiableClass;

class ContainerTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testBindAndResolve()
    {
        $container = Container::getInstance();
        $container->bind('config', function() {
            return new class {
                public function get($key) {
                    return $key;
                }
            };
        });

        $config = $container->make('config');
        $this->assertEquals('test', $config->get('test'));
    }

    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $container = Container::getInstance();
        $container->make('nonexistent');
    }

    public function testBindingResolutionException()
    {
        $this->expectException(BindingResolutionException::class);
        $container = Container::getInstance();
        $container->bind('noninstantiable', NonInstantiableClass::class);
        $container->make('noninstantiable');
    }

    public function testPropertyAccess()
    {
        $container = Container::getInstance();
        $container->bind('config', function() {
            return new class {
                public function get($key) {
                    return $key;
                }
            };
        });

        $config = $container->config;
        $this->assertEquals('test', $config->get('test'));
    }
}
