<?php

namespace App;

use App\Controllers\ControllerLogin;
use App\Core\View;
use App\Models\ModelLogin;
use Exception;

class Container
{
    public function get($className)
    {
        switch ($className) {
            case ModelLogin::class:
                return new ModelLogin();
            case View::class:
                return new View();
            case ControllerLogin::class:
                return new ControllerLogin(
                    $this->get(ModelLogin::class),
                    $this->get(View::class)
                );
            default:
                throw new Exception;
        }
    }
}