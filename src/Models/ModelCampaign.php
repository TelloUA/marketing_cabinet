<?php

namespace App\Models;


use App\DataBase\DbExecutor;
use App\Entity\CampaignType;
use App\Entity\Device;
use App\Core\InputTransformer;

class ModelCampaign
{
    public function list(): array {
        return $this->takeListData();
    }

    public function create(): array {
        $data = $this->takeCreateData();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $validationData = $this->validateCreationData();
            $data = array_merge($data, $validationData);

        }

        return $data;
    }

    //Дуже тимчасове рішення, просто щоб запрацювала основна структура MVC, модель буде якось розділятися далі
    private function takeListData(): array {
        $data = array();
        $user_id = $GLOBALS['user_id'];
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

        $data['name'] = $data['type'] = $data['device'] = $data['geo'] = $data['url'] = '';
        $data['nameErr'] = $data['typesErr'] = $data['deviceErr'] = $data['geoErr'] = $data['urlErr'] = '';

        return $data;
    }

    private function validateCreationData(): array {
        $data = array();
        $data['success_submit'] = true;
        if (empty($_POST["name"])) {
            $data['nameErr'] = "Name is required";
            $data['success_submit'] = false;
        } else {
            $data['name'] = InputTransformer::transform($_POST['name']);
            $name = $data['name'];
            $user_id = $GLOBALS['user_id'];
            $nameExistQuery = "SELECT `name` FROM `campaigns` WHERE `user_id` = '$user_id' AND `name` = '$name'";
            $conn = new DbExecutor(true, $nameExistQuery);
            $conn->execute();
            $nameExist = $conn->getResult();
            if ($nameExist->num_rows > 0) {
                $data['nameErr'] = "Campaign name already exist";
                $data['success_submit'] = false;
            } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $name)) {
                $data['nameErr'] = "Only letters and white space allowed";
                $data['success_submit'] = false;
            } else if (strlen($name) > 255) {
                $data['nameErr'] = "Name should be less than 255";
                $data['success_submit'] = false;
            }
        }

        if (empty($_POST["type"])) {
            $data['typeErr'] = "Campaign type is required";
            $data['success_submit'] = false;
        } else {
            $data['type'] = InputTransformer::transform($_POST["type"]);
            if (!in_array($data['type'], CampaignType::getTypes())) {
                $data['typeErr'] = "Wrong type, select again";
                $data['success_submit'] = false;
            }
        }

        if (empty($_POST["device"])) {
            $data['deviceErr'] = "Device is required";
            $data['success_submit'] = false;
        } else {
            $data['device'] = InputTransformer::transform($_POST["device"]);
            if (!in_array($data['device'], Device::getDevices())) {
                $data['deviceErr'] = "Wrong device, select again";
                $data['success_submit'] = false;
            }
        }

        if (empty($_POST["geo"])) {
            $data['geoErr'] = "Geo is required";
            $data['success_submit'] = false;
        } else {
            $data['geo'] = InputTransformer::transform($_POST["geo"]);
            $geo = $data['geo'];
            $geoExistQuery = "SELECT `id`, `name`, `short_name` FROM `geo` WHERE `name` = '$geo';";
            $conn = new DbExecutor(true, $geoExistQuery);
            $conn->execute();
            $resultGeoExist = $conn->getResult();
            if ($resultGeoExist->num_rows == 0) {
                $data['geoErr'] = "Wrong geo, select again";
                $data['success_submit'] = false;
            } else {
                $data['geoId'] = $resultGeoExist->fetch_assoc()['id'];
            }
        }

        if (empty($_POST["url"])) {
            $data['urlErr'] = "Url is required";
            $data['success_submit'] = false;
        } else {
            $data['url'] = InputTransformer::transform($_POST["url"]);
            if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
                $data['urlErr'] = "Url contains mistakes";
                $data['success_submit'] = false;
            }
        }

        if ($data['success_submit']) {
            $this->addNewCampaign($data);
        }

        return $data;
    }

    private function addNewCampaign(array $data): void {
        $user_id = $GLOBALS['user_id'];
        $name = $data['name'];
        $type = $data['type'];
        $device = $data['device'];
        $geoId = $data['geoId'];
        $url = $data['url'];
        $insertCampaignQuery = "
        INSERT INTO `campaigns` (`user_id`, `name`, `type`, `device`, `geo`, `url`)
        VALUES ('$user_id', '$name', '$type', '$device', '$geoId', '$url');";
        $conn = new DbExecutor(false, $insertCampaignQuery);
        $conn->execute();
    }
}