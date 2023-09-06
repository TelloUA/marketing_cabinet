<?php

namespace App;

class Route
{
    static function start(): void
    {
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $info = $_SERVER['REQUEST_URI'];

        if ( !empty($routes[2]) ) {
            switch ($routes[2]) {
                case 'campaign':
                    if (!empty($routes[3])) {
                        switch ($routes[3]) {
                            case 'list':
                                include 'campaigns_list.php';
                                break;
                            case 'create':
                                include 'campaigns_create.php';
                                break;
                            default:
                                Route::ErrorPage404($info);
                        }
                    } else {
                        Route::ErrorPage404($info);
                    }
                    break;
                case 'profile':
                    include 'profile.php';
                    break;
                case 'authorization':
                    //include 'authorization.php';
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
                case 'index.php':
                    include 'authorization.php';
                    break;
                default:
                    echo '<br>first default<br>';
                    include '404.php';
                    //Route::ErrorPage404($info);
            }
        }
    }

    static function ErrorPage404($info): void
    {
        header('Location: 404.php');
    }
}