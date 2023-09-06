<?php
require_once "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<div class="container">
    <header>
        <!-- <a href='/test'>Test</a> | -->
        <?php if(isset($_COOKIE['id'])) {
            $user_id = $_COOKIE['id'];
            $takeUserName = "SELECT `name` FROM `users` WHERE `id` = '$user_id'";
            $userName = selectConnect($takeUserName)->fetch_assoc();
            if ($userName['name'] == "" || $userName['name'] == NULL) {
                $userNameOutput = "User";
            } else {
                $userNameOutput = $userName['name'];
            }
            echo "<span>Hello ".$userNameOutput. "! | </span>
                  <a href='/profile'>Profile</a> |
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