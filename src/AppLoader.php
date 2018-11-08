<?php

namespace App;

/**
 * Class AppLoader
 * @package App
 */
class AppLoader
{
    /**
     * Run the method requested by the route.
     */
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