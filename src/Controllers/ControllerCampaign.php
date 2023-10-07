<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\ModelCampaign;

class ControllerCampaign
{
    private ModelCampaign $modelCampaign;
    private View $view;

    public function __construct()
    {
        $this->modelCampaign = new ModelCampaign();
        $this->view = new View();
    }

    public function list(): void
    {
        $data = $this->modelCampaign->list();
        $this->view->generate('campaignListView.php', 'templateView.php', $data);
    }

    public function create(): void
    {
        $data = $this->modelCampaign->create();
        if (isset($data['success_submit']) && $data['success_submit']) {
            header('Location: /campaign/list');
        } else {
            $this->view->generate('campaignCreateView.php', 'templateView.php', $data);
        }

    }

    public function delete($id): void {
        $this->modelCampaign->delete($id);
    }
}