<?php

namespace Avresticontainer;

use Avresticontainer\Exceptions\NotFoundException;

/**
 * Class ServiceLocator
 * @package Avresticontainer
 */
class ServiceLocator
{
    /**
     * Dynamically handle static calls to the class.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws NotFoundException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return Container::getInstance()->make($name);
    }
}