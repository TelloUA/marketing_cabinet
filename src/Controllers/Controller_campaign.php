<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Model_campaign;

class Controller_campaign
{
    private Model_campaign $model_campaign;
    private View $view;

    public function __construct()
    {
        $this->model_campaign = new Model_campaign();
        $this->view = new View();
    }

    public function list($user_id): void
    {
        $data = $this->model_campaign->list($user_id);
        $this->view->generate('campaign_list_view.php', 'template_view.php', $data);
    }
}