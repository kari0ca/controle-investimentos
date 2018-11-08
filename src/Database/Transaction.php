<?php

namespace App\Database;

/**
 * Class Transaction
 * @package App\Database
 */
final class Transaction
{
    /** @var \PDO */
    private static $connection;

    /**
     * Transaction constructor.
     * Private constructor so the class cannot be instantiated
     */
    private function __construct()
    {
    }

    /**
     * Open a new connection with database and start a new transaction
     *
     * @param string $config
     *
     * @throws \Exception
     */
    public static function open($config = 'default')
    {
        if (empty(self::$connection)) {
            self::$connection = Connection::open($config);
            self::$connection->beginTransaction();
        }
    }

    /**
     * Get current database connection without the transaction
     *
     * @return \PDO
     */
    public static function get()
    {
        return self::$connection;
    }

    /**
     * Rolls back the transaction in case of fail
     */
    public static function rollback()
    {
        if (self::$connection) {
            self::$connection->rollBack();
            self::$connection = null;
        }
    }

    /**
     * Commit the transaction in case of success.
     */
    public static function close()
    {
        if (self::$connection) {
            self::$connection->commit();
            self::$connection = null;
        }
    }
}