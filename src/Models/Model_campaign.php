<?php

namespace App\Models;


use App\DataBase\DbExecutor;
use App\Entity\CampaignType;
use App\Entity\Device;

class Model_campaign
{
    public function list($user_id): array {
        return $this->takeListData($user_id);
    }

    public function create(): array {
        return $this->takeCreateData();
    }

    public function createValidation(): array|bool {
        //
    }

    //Дуже тимчасове рішення, просто щоб запрацювала основна структура MVC, модель буде якось розділятися далі
    private function takeListData($user_id): array {
        $data = array();
        $query = "SELECT 
                c.`id`,
                c.`user_id`,
                c.`name`,
                c.`type`,
                c.`device`,
                g.`name` as `geo`,
                c.`limit_by_budget`,
                c.`url`,
                c.`when_add`,
                c.`when_change` 
                FROM `campaigns` c 
                LEFT JOIN `geo` g ON g.`id` = c.`geo` 
                WHERE c.`user_id` = '$user_id'";
        $conn = new DbExecutor(true, $query);
        $conn->execute();
        $dataSql = $conn->getResult();
        if ($dataSql->num_rows > 0) {
            while ($row = $dataSql->fetch_assoc()) {
                $data[] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'device' => $row['device'],
                    'geo' => $row['geo'],
                    'url' => $row['url'],
                    'when_add' => $row['when_add']
                );
            }
        }
        return $data;
    }

    private function takeCreateData(): array {
        $data = array();
        $data['types'] = CampaignType::getTypes();
        $data['devices'] = Device::getDevices();
        $countriesQuery = "SELECT `id`, `name`, `short_name` FROM `geo` ORDER BY `name`;";
        $conn = new DbExecutor(true, $countriesQuery);
        $conn->execute();
        $dataSql = $conn->getResult();
        if ($dataSql->num_rows > 0) {
            while ($row = $dataSql->fetch_assoc()) {
                $data['geoList'][] = $row['name'];
            }
        }

        return $data;
    }
}