<?php

namespace App\Database;


final class Connection
{
    static private $database;

    private function __construct()
    {
    }

    /**
     * @param string $config
     *
     * @return \PDO
     * @throws \Exception
     */
    public static function open($database = 'default')
    {
        $realPath = realpath(__DIR__ . '/../../config/config.php');

        if ($realPath) {
            $config = require $realPath;

            if (!isset($config['databases'])) {
                throw new \Exception('No database config set.');
            }

            self::$database = $database;
        }


        $driver = $config['databases'][self::$database]['driver'];

        /** @var \App\Database\Drivers\DatabaseDriverInterface $driver */
        $driver = new $driver($config['databases'][self::$database]);

        $driver->getConnection()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $driver->getConnection();
    }
}