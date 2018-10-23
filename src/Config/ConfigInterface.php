<?php
/**
 * Created by PhpStorm.
 * User: diego182
 * Date: 22/10/18
 * Time: 23:16
 */

namespace App\Config;

/**
 * Interface ConfigInterface
 * Interface for configure drivers
 * @package App\Config
 */
interface ConfigInterface
{

    /**
     * Load a new config from given configuration
     *
     * @param string|null $name
     *
     * @return void
     */
    public function load(string $name = null): void;

    /**
     * Method to return a config key
     *
     * @param string $key The key that should be get
     * @param mixed $default
     *
     * @return array|string|null
     */
    public function get(string $key = null, $default = null);
}