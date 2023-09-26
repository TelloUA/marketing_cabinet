<?php

namespace App;

class Route
{
    static function start(): void
    {
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', strtolower($_SERVER['REQUEST_URI']));

        if (!empty($routes[3])) {
            header('Location: /'.$routes[1].'/'.$routes[2]);
        }

        if (!empty($routes[1])) {
            if (!empty($routes[2])) {

                //double routes by mvc, only controller class is need to exist?
                $controllerName = "App\\Controllers\\Controller".ucfirst($routes[1]);

                if (class_exists($controllerName)) {

                    $controller = new $controllerName();
                    $actionName = $routes[2];
                    if (method_exists($controller, $actionName)) {
                        $controller->$actionName();
                    } else {
                        Route::ErrorPage404();
                    }
                } else {
                    Route::ErrorPage404();
                }

            } else {
                switch ($routes[1]) {
                    case 'authorization':
                        include 'authorization.php';
                        break;
                    case 'registration':
                        include 'registration.php';
                        break;
                    case 'exit':
                        include 'exit.php';
                        break;
                    case 'test':
                        include 'test_link.php';
                        break;
                    case '404':
                        include '404.php';
                        break;
                    default:
                        Route::ErrorPage404();
                }
            }

        } else {
            header('Location: /authorization');
        }

    }

    static function ErrorPage404(): void
    {
        header('Location: /404');
    }
}