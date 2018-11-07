<?php
/**
 * Created by PhpStorm.
 * User: diego182
 * Date: 06/11/18
 * Time: 23:32
 */

namespace App\Database\Drivers;


interface DatabaseDriverInterface
{
    /**
     * @return \PDO
     */
    public function getConnection(): \PDO;
}