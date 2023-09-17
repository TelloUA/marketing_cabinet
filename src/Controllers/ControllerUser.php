<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\ModelUser;

class ControllerUser
{
    private ModelUser $modelUser;

    private View $view;
    public function __construct()
    {
        $this->modelUser = new ModelUser();
        $this->view = new View();
    }

    public function profile(): void
    {
        $data = $this->modelUser->profile();
        $this->view->generate('userProfileView.php', 'templateView.php', $data);

    }
}