<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\ModelLogin;
use Doctrine\DBAL\Exception;

class ControllerLogin
{
    private ModelLogin $modelLogin;

    private View $view;

    public function __construct(ModelLogin $modelLogin, View $view)
    {
        $this->modelLogin = $modelLogin;
        $this->view = $view;
    }

    /**
     * @throws Exception
     */
    public function authorization(): void
    {
        $data = $this->modelLogin->authorization();
        $this->view->generate('loginAuthorizationView.php', 'templateView.php', $data);

    }

    /**
     * @throws Exception
     */
    public function registration(): void
    {
        $data = $this->modelLogin->registration();
        $this->view->generate('loginRegistrationView.php', 'templateView.php', $data);
    }
}