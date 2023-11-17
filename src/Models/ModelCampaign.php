<?php

namespace App\Models;


use App\DataBase\DbConnection;
use App\Entity\Campaigns;
use App\Entity\CampaignType;
use App\Entity\Device;
use App\Core\InputTransformer;
use App\Exceptions\NotFoundCampaignIdException;
use Doctrine\DBAL\Exception;

class ModelCampaign
{
    private Campaigns $campaign;
    private DbConnection $connection;
    private int $userId;

    public function __construct(DbConnection $connection, Campaigns $campaign)
    {
        $this->connection = $connection;
        $this->campaign = $campaign;
        $this->userId = (int)$GLOBALS['user_id'];
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function list(): array {
        return $this->takeCampaignData();
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function create(): array {
        $data = $this->takeOptionsData();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = array_merge($data, $this->validateCreatingData());
        }

        return $data;
    }

    /**
     * @param string $id
     * @return string[]
     * @throws Exception
     */
    public function edit(string $id): array {
        $data = array_merge($this->takeOptionsData(), $this->takeCampaignData($id));

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = array_merge($data, $this->validateEditingData($id));
        }
        return $data;
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function delete(string $id): void {
        $data = $this->validateDeleteCampaign($id);

        if ($this->campaign->isSuccessOperation()) {
            $data = array_merge($data, $this->executeDeleteCampaign());
        } else {
            $data['statusCode'] = '422';
        }

        // return http response code and message for AJAX
        http_response_code($data['statusCode'] ?? '422');
        echo $data['message'] ?? '';
    }

    /**
     * @param int $campaignId
     * @return string[]
     * @throws Exception
     */
    private function takeCampaignData(int $campaignId = 0): array {

        // basic select for campaigns
        $campaignSelect = $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->select('c.id', 'c.name', 'c.type', 'c.device', 'g.name as geo', 'c.url', 'c.when_add')
            ->from('campaigns', 'c')
            ->leftJoin('c', 'geo', 'g', 'c.geo = g.id');

        // single campaign or all campaigns
        if ($campaignId) {
            $this->campaign->setId($campaignId);

            //check whether the campaign belongs to the client
            $this->campaign->validateOwner($this->userId);
            if (!$this->campaign->isSuccessOperation()) {
                throw new NotFoundCampaignIdException($this->campaign->getId());
            }

            $data = $campaignSelect
                ->where('c.id = :campaignId')
                ->setParameter('campaignId', $this->campaign->getId())
                ->fetchAssociative();
        } else {
            $data = $campaignSelect
                ->where('c.user_id = :userId')
                ->andWhere('c.is_deleted = 0')
                ->setParameter('userId', $this->userId)
                ->fetchAllAssociative();
        }

        return $data;
    }

    /**
     * @return string[]
     * @throws Exception
     */
    private function takeOptionsData(): array {
        $data = [];
        $data["types"] = CampaignType::getTypes();
        $data["devices"] = Device::getDevices();
        $countries = $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->select('name')
            ->from('geo')
            ->orderBy('name', 'ASC')
            ->fetchAllAssociative();

        foreach ($countries as $arr) {
            $data["geoList"][] = $arr["name"];
        }

        return $data;
    }

    /**
     * @return string[]
     * @throws Exception
     */
    private function validateCreatingData(): array {
        $data = [];

        // clear input data before validation
        $data['name'] = InputTransformer::transform($_POST['name']);
        $data['type'] = InputTransformer::transform($_POST['type']);
        $data['device'] = InputTransformer::transform($_POST['device']);
        $data['geo'] = InputTransformer::transform($_POST['geo']);
        $data['url'] = InputTransformer::transform($_POST['url']);

        // combine errors from validation
        $data = array_merge(
            $data,
            $this->campaign->validateName($this->userId, $data['name']),
            $this->campaign->validateType($data['type']),
            $this->campaign->validateDevice($data['device']),
            $this->campaign->validateGeo($data['geo']),
            $this->campaign->validateUrl($data['url'])
        );

        if ($this->campaign->isSuccessOperation()) {
            $data['successSubmit'] = true;
            $this->executeCreateCampaign($data);
        }

        return $data;
    }

    /**
     * @param string $id
     * @return string[]
     * @throws Exception
     */
    private function validateEditingData(string $id): array {

        $data = [];

        $this->campaign->setId((int)$id);

        // clear input data before validation
        $data['name'] = InputTransformer::transform($_POST['name']);
        $data['type'] = InputTransformer::transform($_POST['type']);
        $data['device'] = InputTransformer::transform($_POST['device']);
        $data['url'] = InputTransformer::transform($_POST['url']);

        // combine errors from validation
        $data = array_merge(
            $data,
            $this->campaign->validateOwner($this->userId),
            $this->campaign->validateName($this->userId, $data['name']),
            $this->campaign->validateType($data['type']),
            $this->campaign->validateDevice($data['device']),
            $this->campaign->validateUrl($data['url'])
        );

        // if was any errors isSuccessSubmit will be false
        if ($this->campaign->isSuccessOperation()) {
            $data['successSubmit'] = true;
            $this->executeEditCampaign($data);
        }

        return $data;
    }

    /**
     * @param string $id
     * @return string[]
     * @throws Exception
     */
    private function validateDeleteCampaign(string $id): array {

        $this->campaign->setId((int)$id);

        return $this->campaign->validateDelete($this->userId);
    }

    /**
     * @param string[] $data
     * @return void
     * @throws Exception
     */
    private function executeCreateCampaign(array $data): void {
        $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->insert('campaigns')
            ->setValue('user_id', ':userId')
            ->setValue('name', ':name')
            ->setValue('type', ':type')
            ->setValue('device', ':device')
            ->setValue('geo', ':geoId')
            ->setValue('url', ':url')
            ->setParameter('userId', $this->userId)
            ->setParameter('name', $data['name'])
            ->setParameter('type', $data['type'])
            ->setParameter('device', $data['device'])
            ->setParameter('geoId', $data['geoId'])
            ->setParameter('url', $data['url'])
            ->executeStatement();
    }

    /**
     * @param string[] $data
     * @throws Exception
     */
    private function executeEditCampaign(array $data): void
    {
        $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->update('campaigns')
            ->set('name', ':name')
            ->set('type', ':type')
            ->set('device', ':device')
            ->set('url',':url')
            ->setParameter('name', $data['name'])
            ->setParameter('type', $data['type'])
            ->setParameter('device', $data['device'])
            ->setParameter('url', $data['url'])
            ->where('id = :campaignId ')
            ->setParameter('campaignId', $this->campaign->getId())
            ->executeStatement();
    }

    /**
     * @return string[]
     * @throws Exception
     */
    private function executeDeleteCampaign(): array
    {
        $data = [];

        $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->update('campaigns')
            ->set('is_deleted', 1)
            ->where('id = :campaignId')
            ->setParameter('campaignId', $this->campaign->getId())
            ->executeStatement();

        $data['message'] = "Success deleted";
        $data['statusCode'] = "200";

        return $data;
    }

}