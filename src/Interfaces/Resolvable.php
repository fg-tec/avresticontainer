<?php

namespace Avresticontainer\Interfaces;

/**
 * Interface Resolvable
 * @package Avresticontainer\Interfaces
 */
interface Resolvable
{
    /**
     * Get an instance of the class.
     *
     * @return mixed
     */
    public static function getInstance(): mixed;
}
