<?php
// tests/Integration/AutowiringTest.php

namespace Tests\Integration;

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

class ServiceWithDependencies
{
    protected DependencyA $dependencyA;
    protected DependencyB $dependencyB;

    public function __construct(DependencyA $dependencyA, DependencyB $dependencyB)
    {
        $this->dependencyA = $dependencyA;
        $this->dependencyB = $dependencyB;
    }

    public function getDependenciesValues()
    {
        return $this->dependencyA->getValue() . ' ' . $this->dependencyB->getDependencyValue();
    }
}

class AutowiringTest extends TestCase
{
    protected function setUp(): void
    {
        $container = Container::getInstance();
        $container->bindings = [];
        $container->instances = [];
        $container->aliases = [];
    }

    public function testAutowiring()
    {
        $container = Container::getInstance();
        $container->bind(DependencyA::class);
        $container->bind(DependencyB::class);
        $container->bind(ServiceWithDependencies::class);

        $service = $container->make(ServiceWithDependencies::class);
        $this->assertEquals('valueA valueA', $service->getDependenciesValues());
    }
}
