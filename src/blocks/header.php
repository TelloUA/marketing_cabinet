<?php
require_once "functions.php";

$userNameOutput = 'User';

if($GLOBALS['isLogged'] && is_numeric($GLOBALS['user_id'])) {
    $user_id = $GLOBALS['user_id'];
    $takeUserName = "SELECT `name` FROM `users` WHERE `id` = '$user_id'";
    $userName = selectConnect($takeUserName)->fetch_assoc();
    if ($userName['name'] != "" || $userName['name'] != NULL) {
        $userNameOutput = $userName['name'];
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<div class="container">
    <header>
        <!-- <a href='/test'>Test</a> | -->
        <?php
        if ($GLOBALS['isLogged']) {
            echo "<span>Hello ".$userNameOutput. "! | </span>
                  <a href='/user/profile'>Profile</a> |
                  <a href='/campaign/list'>Campaigns</a> |
                  <a href='/exit'>Exit</a>";
        } else {
            echo "<a href='/authorization'>Authorization</a> |
                  <a href='/registration'>Registration</a>";
        } ?>
        <style>
            .error {color: #FF0000;}
            .icon {width:20px;height:20px;}
        </style>
    </header>