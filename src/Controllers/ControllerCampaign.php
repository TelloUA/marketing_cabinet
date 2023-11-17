<?php

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\NotFoundCampaignIdException;
use App\Models\ModelCampaign;
use Doctrine\DBAL\Exception;

class ControllerCampaign
{
    private ModelCampaign $modelCampaign;
    private View $view;

    public function __construct(ModelCampaign $modelCampaign, View $view)
    {
        $this->modelCampaign = $modelCampaign;
        $this->view = $view;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function list(): void
    {
        $data = $this->modelCampaign->list();
        $this->view->generate('campaignListView.php', 'templateView.php', $data);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function create(): void
    {
        $data = $this->modelCampaign->create();
        if (isset($data['successSubmit']) && $data['successSubmit']) {
            header('Location: /campaign/list');
        } else {
            $this->view->generate('campaignCreateView.php', 'templateView.php', $data);
        }

    }

    /**
     * @param $id
     * @return void
     * @throws Exception
     */
    public function delete($id): void
    {
        // має валідувати чи це число?

        $this->modelCampaign->delete($id);
    }

    /**
     * @param $id
     * @return void
     * @throws Exception
     */
    public function edit($id): void
    {
        if (!is_numeric($id)) {
            header('Location: /campaign/list');
        }
        try {
            $data = $this->modelCampaign->edit($id);
        } catch(NotFoundCampaignIdException) {
            header('Location: /campaign/list');
        }

        $this->view->generate('campaignEditView.php', 'templateView.php', $data);
    }
}