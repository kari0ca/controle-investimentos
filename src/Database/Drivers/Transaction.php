<?php

namespace App\Database\Drivers;


use App\Database\Connection;

final class Transaction
{
    /** @var \PDO */
    private static $connection;

    private function __construct()
    {
    }

    public static function open($config = 'default')
    {
        if (empty(self::$connection)) {
            self::$connection = Connection::open($config);
            self::$connection->beginTransaction();
        }
    }

    public static function get()
    {
        return self::$connection;
    }

    public static function rollback()
    {
        if (self::$connection) {
            self::$connection->rollBack();
            self::$connection = null;
        }
    }

    public static function close()
    {
        if (self::$connection) {
            self::$connection->commit();
            self::$connection = null;
        }
    }
}