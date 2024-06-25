<?php

namespace Avresticontainer\Interfaces;

/**
 * Interface ContainerInterface
 * @package Avresticontainer\Interfaces
 */
interface ContainerInterface
{
    /**
     * Bind an abstract type to a concrete type.
     *
     * @param string $abstract
     * @param mixed|null $concrete
     * @return void
     */
    public function bind(string $abstract, mixed $concrete = null): void;

    /**
     * Register a singleton in the container.
     *
     * @param string $abstract
     * @param mixed|null $concrete
     * @return void
     */
    public function singleton(string $abstract, mixed $concrete = null): void;

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @return mixed
     */
    public function make(string $abstract): mixed;

    /**
     * Register an alias for an abstract type.
     *
     * @param string $abstract
     * @param string $alias
     * @return void
     */
    public function alias(string $abstract, string $alias): void;

}
