<?php

include "../include/init.php";

Header("Content-Type: application/json");

if (!isset($_POST["login"]) || !isset($_POST["password"])) {
    echo (json_encode(["status" => "error", "message" => "Невірні дані"]));
    exit();
}

$login = $_POST["login"];
$password = md5($_POST["password"]);

$result = $db->find("accounts")
    ->select("*")
    ->where("UserName", "=", $login)
    ->where("Password", "=", $password)
    ->one();

if ($result && count($result) > 0) {
    session_start();
    $_SESSION["currentAccount"] = $result;
    echo (json_encode(["status" => "success", "message" => "Успішний вхід"]));
} else {
    echo (json_encode(["status" => "error", "message" => "Невірний логін або пароль"]));
}
