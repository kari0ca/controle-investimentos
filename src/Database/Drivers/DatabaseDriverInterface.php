<?php

namespace App\Database\Drivers;


interface DatabaseDriverInterface
{
    /**
     * Return a new PDO instance for a givem instance
     *
     * @return \PDO
     */
    public function getConnection(): \PDO;
}