<?php
/**
 * System front controller.
 */
require_once '../vendor/autoload.php';
require_once '../config/config.php';
session_start();
require_once '../config/session.php';

use App\AppLoader;

$app = new AppLoader();
$app->run();