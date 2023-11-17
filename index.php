<?php

ini_set('display_errors', 1);
require_once 'vendor/autoload.php';

use App\Route;

$isLogged = false;
$user_id = null;
if(isset($_COOKIE['auth_token'])) {
    $decode = base64_decode($_COOKIE['auth_token'], true);
    if ($decode) {
        $isLogged = true;
        $user_id = $decode;
    }
}

Route::start();

/*
 * Fact or potential routes
 * campaign/list
 * campaign/create
 * campaign/edit/$id
 * campaign/delete/$id
 * user/profile
 * login/authorization
 * login/registration
 * ? login/exit
 * test
 */


