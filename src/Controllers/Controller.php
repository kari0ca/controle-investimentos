<?php

namespace App\Controllers;


abstract class Controller
{
    public function show()
    {
        if ($_GET) {
            $class = $_GET['controller'] ?? null;
            $method = $_GET['method'] ?? 'index';

            if ($class) {
                $object = $class === get_class($this) ? $this : new $class;

                if (method_exists($object, $method)) {
                    call_user_func([$object, $method], $_GET);
                }
            }
        }
    }
}