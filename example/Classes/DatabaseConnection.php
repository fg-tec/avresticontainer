<?php

namespace Example\Classes;

/**
 * Class DatabaseConnection
 * Simulates a database connection.
 *
 * @package Example\Classes
 */
class DatabaseConnection
{
    protected Config $config;

    /**
     * DatabaseConnection constructor.
     *
     * @param Config $config Configuration manager
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Simulate a database connection.
     *
     * @return string Connection status
     */
    public function connect(): string
    {
        $host = $this->config->get('db_host', 'localhost');
        $dbname = $this->config->get('db_name', 'example_db');
        return "Connected to database '{$dbname}' at '{$host}'<br>";
    }
}