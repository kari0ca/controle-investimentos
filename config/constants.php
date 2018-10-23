<?php
/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * Config dir
 */
define('CONFIG_DIR', ROOT . '/config');

/**
 * Vendor dir
 */
define('VENDOR_DIR', ROOT . '/vendor');

/**
 * Src dir
 */
define('SRC_DIR', ROOT . '/src');