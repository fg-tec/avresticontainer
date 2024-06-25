<?php

namespace Example\Classes;

use Avresticontainer\Traits\ServiceResolver;

/**
 * Class AppService
 * Example service using the ServiceResolver trait for dynamic access.
 *
 * @package Example\Classes
 */
class AppService
{
    use ServiceResolver;

    /**
     * Run the application service.
     *
     * @return string Service status
     */
    public function run(): string
    {
        return "Service is running.";
    }
}