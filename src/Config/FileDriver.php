<?php

namespace App\Config;


use Adbar\Dot;

class FileDriver implements ConfigInterface
{
    /** @var Dot */
    private $config;

    /**
     * Load a new config from given configuration
     *
     * @param string|null $name
     *
     * @return mixed
     * @throws \App\Config\InvalidConfigDriverException
     */
    public function load(string $name = null): void
    {
        $file = realpath(CONFIG_DIR . '/' . $name . '.php');
        if (pathinfo($file)) {
            $this->config = new Dot(require_once $file);

            return;
        }

        throw new InvalidConfigDriverException();
    }

    /**
     * Method to return a config key
     *
     * @param string $key The key that should be get
     * @param mixed $default
     *
     * @return array|string|null
     */
    public function get(string $key = null, $default = null)
    {
            dump($key, $this->config->get($key, $default));

        return $this->config->get($key, $default);
    }
}