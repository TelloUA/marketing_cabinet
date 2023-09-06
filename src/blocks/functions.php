<?php
require_once "config.php";
function test_input($data, $isEmail = false): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    //setup email to lower case
    if ($isEmail) {
        $data = strtolower($data);
    }
    return $data;
}

function connect() {
    $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
    $connection->query("SET NAMES 'utf8'");
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}

function close($connection): void
{
    $connection->close();
}

function noReturnConnect($query): void
{
    $connection = connect();
    $connection->query($query);
    close($connection);
}

function selectConnect($query): mysqli_result|bool
{
    $connection = connect();
    $result = $connection->query($query);
    close($connection);
    return $result;
}

function drawSelectOptions($array, $option): string
{
    $allRows = '';
    foreach ($array as $item) {
        $row = '<option value="'.$item.'"';
        if ($option == $item) {
            $row .= ' selected';
        }
        $row .= '>'.$item.'</option>';
        $allRows .= $row;
    }
    return $allRows;
}