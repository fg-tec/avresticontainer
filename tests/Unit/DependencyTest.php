<?php
// tests/Unit/DependencyTest.php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Avresticontainer\Container;

class DependencyA
{
    public function getValue()
    {
        return 'valueA';
    }
}

class DependencyB
{
    protected DependencyA $dependencyA;

    public function __construct(DependencyA $dependencyA)
    {
        $this->dependencyA = $dependencyA;
    }

    public function getDependencyValue()
    {
        return $this->dependencyA->getValue();
    }
}

class DependencyTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testDependencies()
    {
        $container = Container::getInstance();
        $container->bind(DependencyA::class);
        $container->bind(DependencyB::class);

        $dependencyB = $container->make(DependencyB::class);
        $this->assertEquals('valueA', $dependencyB->getDependencyValue());
    }
}
