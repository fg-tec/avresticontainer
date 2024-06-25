<?php

namespace Avresticontainer\Interfaces;

/**
 * Interface FactoryInterface
 * @package Avresticontainer\Interfaces
 */
interface FactoryInterface
{
    /**
     * Create an instance of the given type.
     *
     * @param ContainerInterface $container
     * @param array $parameters
     * @return mixed
     */
    public function create(ContainerInterface $container, array $parameters = []): mixed;
}