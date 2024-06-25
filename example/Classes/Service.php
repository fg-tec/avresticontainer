<?php

namespace Example\Classes;

/**
 * Class Service
 * Example service that uses a database connection.
 *
 * @package Example\Classes
 */
class Service
{
    protected DatabaseConnection $db;

    /**
     * Service constructor.
     *
     * @param DatabaseConnection $db Database connection
     */
    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Perform a task using the database connection.
     *
     * @return string Task result
     */
    public function performTask(): string
    {
        return $this->db->connect();
    }
}