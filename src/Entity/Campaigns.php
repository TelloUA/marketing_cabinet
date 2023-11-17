<?php

namespace App\Entity;

use App\DataBase\DbConnection;
use Doctrine\DBAL\Exception;

class Campaigns
{
    private DbConnection $connection;
    private bool $isSuccessOperation = true;
    private int $id = 0;

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return bool
     */
    public function isSuccessOperation(): bool
    {
        return $this->isSuccessOperation;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $userId
     * @return string[]
     * @throws Exception
     */
    public function validateOwner(int $userId): array
    {
        $data = [];

        $userCampaigns = $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->select('id')
            ->from('campaigns')
            ->where('user_id = :userId')
            ->andWhere('id = :campaignId')
            ->setParameter('userId', $userId)
            ->setParameter('campaignId', $this->getId())
            ->fetchAllAssociative();

        if (count($userCampaigns) == 0) {
            $data['campaignErr'] = 'Campaign not exist';
            $this->isSuccessOperation = false;
        }
        return $data;
    }

    /**
     * @param int $userId
     * @param string $name
     * @return string[]
     * @throws Exception
     */
    public function validateName(int $userId, string $name): array
    {
        $data = [];
        if (empty($name)) {
            $data['nameErr'] = 'Name is required';
            $this->isSuccessOperation = false;
        } else {
            $nameExist = $this
                ->connection
                ->getConnection()
                ->createQueryBuilder()
                ->select('c.id', 'c.name')
                ->from('campaigns', 'c')
                ->where('c.user_id = :userId')
                ->andWhere('c.name = :name')
                ->andWhere('c.id != :id')
                ->setParameter('userId', $userId)
                ->setParameter('name', $name)
                ->setParameter('id', $this->getId())
                ->fetchAllAssociative();

            if (count($nameExist) > 0) {
                $data['nameErr'] = 'Campaign name already exist';
                $this->isSuccessOperation = false;
            } else if (!preg_match('/^[a-zA-Z0-9 ]*$/', $name)) {
                $data['nameErr'] = 'Only letters, numbers and white space allowed';
                $this->isSuccessOperation = false;
            } else if (strlen($name) > 255) {
                $data['nameErr'] = 'Name should be less than 255';
                $this->isSuccessOperation = false;
            }
        }
        return $data;
    }

    /**
     * @param string $type
     * @return string[]
     */
    public function validateType(string $type): array
    {
        $data = [];
        if (empty($type)) {
            $data["typeErr"] = "Campaign type is required";
            $this->isSuccessOperation = false;
        } else {
            if (!in_array($type, CampaignType::getTypes())) {
                $data["typeErr"] = "Wrong type, select again";
                $this->isSuccessOperation = false;
            }
        }
        return $data;
    }

    /**
     * @param string $device
     * @return string[]
     */
    public function validateDevice(string $device): array
    {
        $data = [];
        if (empty($device)) {
            $data['deviceErr'] = 'Device is required';
            $this->isSuccessOperation = false;
        } else {
            if (!in_array($device, Device::getDevices())) {
                $data['deviceErr'] = 'Wrong device, select again';
                $this->isSuccessOperation = false;
            }
        }
        return $data;
    }

    /**
     * @param string $geo
     * @return string[]
     * @throws Exception
     */
    public function validateGeo(string $geo): array
    {
        $data = [];

        if (empty($geo)) {
            $data['geoErr'] = 'Geo is required';
            $this->isSuccessOperation = false;
        } else {
            $validGeo = $this
                ->connection
                ->getConnection()
                ->createQueryBuilder()
                ->select('id', 'name')
                ->from('geo')
                ->where('name = :name')
                ->setParameter('name', $geo)
                ->fetchAllAssociative();

            if (count($validGeo) == 0) {
                $data["geoErr"] = "Wrong geo, select again";
                $this->isSuccessOperation = false;
            } else {
                $data["geoId"] = $validGeo[0]['id'];
            }
        }
        return $data;
    }

    /**
     * @param string $url
     * @return string[]
     */
    public function validateUrl(string $url): array
    {
        $data = [];
        if (empty($url)) {
            $data['urlErr'] = 'Url is required';
            $this->isSuccessOperation = false;
        } else {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $data['urlErr'] = 'Url contains mistakes';
                $this->isSuccessOperation = false;
            }
        }
        return $data;
    }

    /**
     * @param int $userId
     * @return string[]
     * @throws Exception
     */
    public function validateDelete(int $userId): array
    {
        $data = [];

        $isDeleted = $this
            ->connection
            ->getConnection()
            ->createQueryBuilder()
            ->select('c.is_deleted')
            ->from('campaigns', 'c')
            ->where('c.user_id = :userId')
            ->andWhere('c.id = :campaignId')
            ->setParameter('userId', $userId)
            ->setParameter('campaignId', $this->getId())
            ->fetchAllAssociative();

        if (count($isDeleted) == 0) {
            $data['message'] = "Campaign doesn't exist";
            $this->isSuccessOperation = false;
        } else {
            if ($isDeleted[0]["is_deleted"]) {
                $data['message'] = "Campaign is already deleted";
                $this->isSuccessOperation = false;
            }
        }

        return $data;
    }
}