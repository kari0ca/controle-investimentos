<?php

namespace App\Database\Drivers;

/**
 * Class MySQL
 * @package App\Database\Drivers
 */
class MySQL implements DatabaseDriverInterface
{
    /** @var array */
    private $settings;

    /**
     * MySQL constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Open a new connection with a MySQL database on givem configuration
     *
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        $hostname = $this->settings['hostname'] ?? 'localhost';
        $username = $this->settings['username'] ?? null;
        $password = $this->settings['password'] ?? null;
        $database = $this->settings['database'] ?? null;
        $port = $this->settings['port'] ?? 3306;

        return new \PDO("mysql:host={$hostname};port={$port};dbname={$database}", $username, $password);
    }
}