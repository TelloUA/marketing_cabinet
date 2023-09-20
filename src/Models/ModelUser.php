<?php

namespace App\Models;

use App\Core\InputTransformer;
use App\DataBase\DbExecutor;
use App\Entity\CommunicationChannelType;

class ModelUser
{
    public function profile(): array {
        $data = $this->takeProfileData();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $validationData = $this->validationProfileData();
            $data = array_merge($data, $validationData);
        }
        return $data;
    }

    private function takeProfileData(): array {
        $data = array();
        $user_id = $GLOBALS['user_id'];
        $profileDataQuery = "SELECT `id`, `name`, `email`, `country`, `company`, 
       `communication_channel`, `communication_info` FROM `users` WHERE `id` = '$user_id'";
        $conn = new DbExecutor(true, $profileDataQuery);
        $conn->execute();
        $dataSql = $conn->getResult();
        if ($dataSql->num_rows > 0) {
            $data = $dataSql->fetch_assoc();
        }

        $data['communicationChannelTypes'] = CommunicationChannelType::getCommunicationChannels();
        $data['nameErr'] = $data['emailErr'] = $data['countryErr'] = $data['companyErr'] = '';
        $data['successSubmit'] = false;

        return $data;
    }

    private function validationProfileData(): array {
        $data = array();
        $data['successSubmit'] = true;
        $data['name'] = InputTransformer::transform($_POST["name"]);
        if (empty($_POST["name"])) {
            $data['nameErr'] = "Name is required";
            $data['successSubmit'] = false;
        } else {
            if (!preg_match("/^[a-zA-Z ]*$/",$data['name'])) {
                $data['nameErr'] = "Only letters and white space allowed";
                $data['successSubmit'] = false;
            } else if (strlen($data['name']) > 40) {
                $data['nameErr'] = "Name should be less than 40";
                $data['successSubmit'] = false;
            }
        }
        $data['email'] = InputTransformer::transform($_POST["email"]);
        if (empty($_POST["email"])) {
            $data['emailErr'] = "Email is required";
            $data['successSubmit'] = false;
        } else {
            $user_id = $GLOBALS['user_id'];
            $email = $data['email'];
            $emailExistQuery = "SELECT `email` FROM `users` WHERE `email` = '$email' AND `id` <> '$user_id'";
            $conn = new DbExecutor(true, $emailExistQuery);
            $conn->execute();
            $emailExistSql = $conn->getResult();
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['emailErr'] = "Invalid email format";
                $data['successSubmit'] = false;
            } else if ($emailExistSql->num_rows > 0) {
                $data['emailErr'] = "Email already exist";
                $data['successSubmit'] = false;
            }
        }
        $data['country'] = InputTransformer::transform($_POST["country"]);
        if (empty($_POST["country"])) {
            $data['countryErr'] = "Country is required";
            $data['successSubmit'] = false;
        } else {
            if (strlen($data['country']) > 40) {
                $data['countryErr'] = "Country should be less than 40";
                $data['successSubmit'] = false;
            }
        }
        $data['company'] = InputTransformer::transform($_POST["company"]);
        if (strlen($data['company']) > 40) {
            $data['companyErr'] = "Name should be less than 40";
            $data['successSubmit'] = false;
        }
        $data['communication_channel'] = $_POST["channel"];
        $data['communication_info'] = InputTransformer::transform($_POST["communicationInfo"]);

        if ($data['successSubmit']) {
            $this->updateProfile($data);
        }

        return $data;
    }

    private function updateProfile(array $data): void {
        $name = $data['name'];
        $email = $data['email'];
        $country = $data['country'];
        $company = $data['company'];
        $communicationChannel = $data['communication_channel'];
        $communicationInfo = $data['communication_info'];
        $user_id = $GLOBALS['user_id'];
        $updateProfileQuery = "UPDATE `users` 
                    SET `name` = '$name', 
                        `email` = '$email',
                        `country` = '$country',
                        `company` = '$company',
                        `communication_channel` = '$communicationChannel',
                        `communication_info` = '$communicationInfo' 
                    WHERE `id` = '$user_id'";
        $conn = new DbExecutor(false, $updateProfileQuery);
        $conn->execute();
    }
}