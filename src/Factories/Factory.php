<?php

namespace Avresticontainer\Factories;

use Avresticontainer\Interfaces\ContainerInterface;
use Avresticontainer\Interfaces\FactoryInterface;

/**
 * Class Factory
 * @package MyContainer\Factories
 */
class Factory implements FactoryInterface
{
    /**
     * The concrete type to be resolved.
     *
     * @var mixed
     */
    protected mixed $concrete;

    /**
     * Factory constructor.
     *
     * @param mixed $concrete
     */
    public function __construct(mixed $concrete)
    {
        $this->concrete = $concrete;
    }

    /**
     * @inheritDoc
     */
    public function create(ContainerInterface $container, array $parameters = []): mixed
    {
        if ($this->concrete instanceof \Closure) {
            return ($this->concrete)($container, $parameters);
        }

        return $container->build($this->concrete, $parameters);
    }
}