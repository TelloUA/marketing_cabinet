<?php
ini_set('display_errors', 1);
require 'vendor/autoload.php';

use App\Route;

$isLogged = false;
$user_id = null;
if(isset($_COOKIE['id'])) {
    $isLogged = true;
    $user_id = $_COOKIE['id'];
}

Route::start();

/*
 * campaign/list
 * campaign/create
 * ? user/profile
 * ? login/authorization
 * ? login/registration
 * ? login/exit
 * test
 */


