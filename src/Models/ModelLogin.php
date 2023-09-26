<?php

namespace App\Models;

use App\Core\InputTransformer;
use App\DataBase\DbExecutor;

class ModelLogin
{

    public function authorization(): array
    {
        return $this->validationAuthorizationData();
    }

    private function validationAuthorizationData(): array {

        $data['email'] = $data['emailErr'] = $data['authErr'] = '';
        $mainErr = "Email and password didn't match";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["email"])) {
                $data['emailErr'] = "Email is required";
            } else {
                $email = InputTransformer::transform($_POST["email"], true);
                $data['email'] = $email;
                $userDataQuery = "SELECT `id`, `email`, `password` FROM `users` WHERE `email` = '$email'";
                $conn = new DbExecutor(true, $userDataQuery);
                $conn->execute();
                $userData = $conn->getResult();
                if ($userData->num_rows == 1) {
                    if (empty($_POST["pwd"])) {
                        $data['authErr'] = $mainErr;
                    } else {
                        $pwd = InputTransformer::transform($_POST["pwd"]);
                        $pwd_hash = md5($pwd);
                        $row = $userData->fetch_assoc();
                        if ($row['password'] == $pwd_hash) {
                            $this->setCookie($row['id']);
                        } else {
                            $data['authErr'] = $mainErr;
                        }
                    }
                } else if ($userData->num_rows > 1) {
                    $data['authErr'] = "Go to support with your email";
                } else {
                    $data['authErr'] = $mainErr;
                }
            }
        }
        return $data;
    }

    private function setCookie(int $id): void
    {

        setcookie('id', $id, time() + 1800, '/');
        header('Location: /user/profile');
    }
}