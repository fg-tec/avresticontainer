<?php

namespace Example\Classes;

/**
 * Class Config
 * A simple configuration manager.
 *
 * @package Example\Classes
 */
class Config
{
    protected array $settings = [];

    /**
     * Config constructor.
     *
     * @param array $settings Configuration settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get a configuration value.
     *
     * @param string $key Configuration key
     * @param mixed $default Default value if key does not exist
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }
}