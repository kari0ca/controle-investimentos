<?php
/**
 * System front controller.
 */
require_once '../vendor/autoload.php';
require_once '../config/config.php';
require_once '../config/session.php';

use App\AppLoader;

session_start();

$app = new AppLoader();
$app->run();