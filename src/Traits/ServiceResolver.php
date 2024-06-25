<?php

namespace Avresticontainer\Traits;

use Avresticontainer\Container;
use Avresticontainer\Exceptions\NotFoundException;

/**
 * Trait ServiceResolver
 * @package Avresticontainer\Traits
 */
trait ServiceResolver
{
    /**
     * Dynamically handle static calls to the class.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws NotFoundException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $instance = Container::getInstance()->make(static::class);
        return call_user_func_array([$instance, $method], $arguments);
    }
}