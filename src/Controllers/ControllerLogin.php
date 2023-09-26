<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\ModelLogin;

class ControllerLogin
{
    private ModelLogin $modelLogin;

    private View $view;

    public function __construct()
    {
        $this->modelLogin = new ModelLogin();
        $this->view = new View();
    }

    public function authorization(): void
    {
        $data = $this->modelLogin->authorization();
        $this->view->generate('loginAuthorizationView.php', 'templateView.php', $data);

    }
}