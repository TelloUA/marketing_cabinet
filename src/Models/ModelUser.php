<?php

namespace App\Models;

use App\DataBase\DbExecutor;

class ModelUser
{
    public function profile(): array {
        return $this->takeProfileData();
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

        $data['nameErr'] = $data['emailErr'] = $data['countryErr'] = $data['companyErr'] = '';
        $data['successSubmit'] = true;

        return $data;
    }
}