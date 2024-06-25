<?php

use PHPUnit\Framework\TestCase;
use Avresticontainer\Container;

class AliasTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testAlias()
    {
        $container = Container::getInstance();
        $container->bind('config', function() {
            return new class {
                public function get($key) {
                    return $key;
                }
            };
        });

        $container->alias('config', 'cfg');

        $config = $container->make('cfg');
        $this->assertEquals('test', $config->get('test'));
    }
}
