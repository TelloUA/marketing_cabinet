<?php

namespace App;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

class Route
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    static function start(): void
    {
        $controller_name = 'Main';
        $action_name = 'index';
        $container = new Container();

        $routes = explode('/', strtolower($_SERVER['REQUEST_URI']));
/*
        if (!empty($routes[3])) {
            header('Location: /'.$routes[1].'/'.$routes[2]);
        }
*/
        if (!empty($routes[1])) {
            if ($routes[1] === 'images' && !empty($routes[2])) {
                // Handle image requests from the /images route
                $imagePath = '/images/' . $routes[2];
                if (file_exists($imagePath)) {
                    // Set appropriate headers for image files
                    readfile($imagePath);
                    exit();
                }
            } else if (!empty($routes[2])) {

                //double routes by mvc, only controller class is need to exist?
                $controllerName = 'App\\Controllers\\Controller'.ucfirst($routes[1]);

                if (class_exists($controllerName)) {

                    $controller = $container->get($controllerName);

                    $actionName = $routes[2];
                    if (method_exists($controller, $actionName)) {

                        if (!empty($routes[3])) {
                            $param = $routes[3];
                            $controller->$actionName($param);
                        } else {
                            $controller->$actionName();
                        }
                    } else {
                        Route::ErrorPage404();
                    }
                } else {
                    Route::ErrorPage404();
                }

            } else {
                switch ($routes[1]) {
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
            header('Location: /login/authorization');
        }

    }

    static function ErrorPage404(): void
    {
        header('Location: /404');
    }
}