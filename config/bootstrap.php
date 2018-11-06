<?php
require_once CONFIG_DIR . '/requirements.php';

/**
 * Verifies if the session exists, if not will start
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

