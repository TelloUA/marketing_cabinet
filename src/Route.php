<?php

namespace App;

use App\Controllers\ControllerCampaign;

class Route
{
    static function start(): void
    {
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $info = $_SERVER['REQUEST_URI'];
 /*
        if ($GLOBALS['user_id'] === null) {
            include 'authorization.php';
            return;
        }
 */

        if ( !empty($routes[1]) ) {
            switch ($routes[1]) {
                case 'campaign':
                    if (!empty($routes[2])) {
                        switch ($routes[2]) {
                            case 'list':
                                include "models/ModelCampaign.php";
                                include "controllers/ControllerCampaign.php";
                                $controller = new ControllerCampaign();
                                $controller->list();
                                break;
                            case 'create':
                                include "models/ModelCampaign.php";
                                include "controllers/ControllerCampaign.php";
                                $controller = new ControllerCampaign();
                                $controller->create();
                                break;
                            default:
                                Route::ErrorPage404();
                        }
                    } else {
                        Route::ErrorPage404();
                    }
                    break;
                case 'profile':
                    include 'profile.php';
                    break;
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
    }

    static function ErrorPage404(): void
    {
        header('Location: /404');
    }
}