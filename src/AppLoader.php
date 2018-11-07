<?php

namespace App;

class AppLoader
{
    public function run()
    {
        try {
            if ($_GET) {
                $class = $_GET['controller'] ?? null;

                if ($class) {
                    /** @var \App\Controllers\Controller $object */
                    $class = $class . 'Controller';

                    $object = new $class;

                    $object->show();
                }
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}