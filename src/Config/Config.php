<?php

namespace App\Config;


class Config
{
    /** @var self */
    private static $instance;
    /** @var \App\Config\ConfigInterface */
    private $driver;

    /**
     * Config constructor.
     * Creates a new instance is it not exists, else return the existing one
     *
     * @param string $config
     * @param string $driver
     *
     * @throws \App\Config\InvalidConfigDriverException
     */
    private function __construct(string $config, string $driver)
    {
        $this->loadDriver($config, $driver);
        $this->driver->load($config);
    }

    /**
     * Load a new config driver
     *
     * @param string $config
     * @param string $driver
     *
     * @throws \App\Config\InvalidConfigDriverException
     */
    private function loadDriver(string $config, string $driver)
    {
        $this->driver = new $driver($config);
        if (!$this->driver instanceof ConfigInterface) {
            throw new InvalidConfigDriverException();
        }
    }

    /**
     * @param string $config
     * @param string $driver
     *
     * @return \App\Config\Config
     * @throws \App\Config\InvalidConfigDriverException
     */
    public static function getInstance(string $config = 'config', string $driver = FileDriver::class): self
    {
        if (self::$instance === null) {
            self::$instance = new Config($config, $driver);
        }

        return self::$instance;
    }

    /**
     * Get a config from an array
     *
     * @param string|null $key
     * @param null $default
     *
     * @return array|null|string
     */
    public function get(string $key = null, $default = null)
    {
        return $this->driver->get($key, $default);
    }
}