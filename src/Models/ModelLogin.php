<?php

namespace App\Models;

use App\Core\InputTransformer;
use App\DataBase\DbConnection;
use Doctrine\DBAL\Exception;

class ModelLogin
{

    private DbConnection $connection;

    public function __construct(DbConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    // ?? в кожній функції тепер всюди exception, бо конекшин може його віддати, не розумію наскільки це гарно
    public function authorization(): array
    {
        return $this->validationAuthorizationData();
    }


    /**
     * @throws Exception
     */
    public function registration(): array
    {
        return $this->validationRegistrationData();
    }

    /**
     * @throws Exception
     */
    private function validationAuthorizationData(): array {

        $data["email"] = $data["emailErr"] = $data["authErr"] = "";
        $mainErr = "Email and password didn't match";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["email"])) {
                $data["emailErr"] = "Email is required";
            } else {
                $data["email"] = InputTransformer::transform($_POST["email"], true);

                 $userData = $this
                     ->connection
                     ->getConnection()
                     ->createQueryBuilder()
                     ->select("id", "email", "password")
                     ->from("users")
                     ->where("email = :email")
                     ->setParameter("email", $data["email"])
                     ->fetchAllAssociative();

                if (count($userData) == 1) {
                    if (empty($_POST["pwd"])) {
                        $data["authErr"] = $mainErr;
                    } else {
                        $pwd_hash = md5(InputTransformer::transform($_POST["pwd"]));

                        if ($userData[0]["password"] == $pwd_hash) {
                            $this->setAuthCookie($userData[0]["id"]);
                        } else {
                            $data["authErr"] = $mainErr;
                        }
                    }
                } else if (count($userData) > 1) {
                    $data["authErr"] = "Go to support with your email";
                } else {
                    $data["authErr"] = $mainErr;
                }
            }
        }
        return $data;
    }

    private function setAuthCookie(int $id): void
    {
        $token = base64_encode($id);
        setcookie("auth_token", $token, time() + 1800, "/");
        header("Location: /user/profile");
    }


    /**
     * @throws Exception
     */
    private function validationRegistrationData(): array {

        $data["email"] = $data["pwd"] = ""; //values in forms
        $data["emailErr"] = $data["pwdErr"] = ""; //error in forms
        $data["success"] = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // email validation
            if (empty($_POST["email"])) {
                $data["emailErr"] = "Email is required";
            } else {
                $data["email"] = InputTransformer::transform($_POST["email"], true);

                $emails = $this
                    ->connection
                    ->getConnection()
                    ->createQueryBuilder()
                    ->select("email")
                    ->from("users")
                    ->where("email = :email")
                    ->setParameter("email", $data["email"])
                    ->fetchAllAssociative();

                if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                    $data["emailErr"] = "Invalid email format";
                } else if (strlen($data["email"]) > 255) {
                    $data["emailErr"] = "Email to long";
                } else if (count($emails) > 0) {
                    $data["emailErr"] = "Email already exist";
                }
            }

            // password validation
            if (empty($_POST["pwd"])) {
                $data["pwdErr"] = "Password is required";
            } else {
                $data["pwd"] = InputTransformer::transform($_POST["pwd"]);
                if (strlen($data["pwd"]) < 8) {
                    $data["pwdErr"] = "Password to short";
                } else if (strlen($data["pwd"]) > 255) {
                    $data["pwdErr"] = "Password to long";
                } else if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_]).*$/", $data["pwd"])) {
                    $data["pwdErr"] = "Password does not meet the requirements";
                }
            }

            // insert new user data
            if (!$data["emailErr"] && !$data["pwdErr"]) {

                $pwd_hash = md5($data["pwd"]);

                $insert = $this
                    ->connection
                    ->getConnection()
                    ->createQueryBuilder()
                    ->insert("users")
                    ->setValue("email", ":email")
                    ->setValue("password", ":password")
                    ->setParameter("email", $data["email"])
                    ->setParameter("password", $pwd_hash)
                    ->executeStatement();

                //check affected rows
                if($insert > 0) {
                    $data["success"] = true;
                }

            }
        }

        return $data;
    }
}