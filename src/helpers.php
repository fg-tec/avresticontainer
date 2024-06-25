<?php

namespace Avresticontainer;

use Avresticontainer\Exceptions\NotFoundException;

/**
 * Bind an abstract type to a concrete type in the container.
 *
 * @param string $abstract
 * @param mixed|null $concrete
 * @return void
 */
function bind(string $abstract, mixed $concrete = null): void
{
    Container::getInstance()->bind($abstract, $concrete);
}

/**
 * Register a singleton in the container.
 *
 * @param string $abstract
 * @param mixed|null $concrete
 * @return void
 */
function singleton(string $abstract, mixed $concrete = null): void
{
    Container::getInstance()->singleton($abstract, $concrete);
}

/**
 * Resolve the given type from the container.
 *
 * @param string $abstract
 * @return mixed
 * @throws NotFoundException
 */
function resolve(string $abstract): mixed
{
    return Container::getInstance()->make($abstract);
}

/**
 * Get a configuration value.
 *
 * @param string|null $key
 * @param mixed|null $default
 * @return mixed
 * @throws NotFoundException
 */
function config(string $key = null, mixed $default = null): mixed
{
    $config = Container::getInstance()->make('ConfigManager');
    if (is_null($key)) {
        return $config;
    }

    return $config->get($key, $default);
}
