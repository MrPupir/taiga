<?php

include "../include/init.php";

Header("Content-Type: application/json");

$id = $_POST["id"];
$from = $_POST["from"];
$to = $_POST["to"];

if (!isset($id) || !isset($from) || !isset($to)) {
    echo json_encode(["status" => "error", "message" => "Невірні дані"]);
    exit();
}

$from = str_replace("/", "-", $from);
$from = strtotime($from);
$to = str_replace("/", "-", $to);
$to = strtotime($to);

$reserved = $db->find("reservations")
    ->where("RoomID", "eq", $id)
    ->openBracket("AND")
    ->whereGroup([
        ["reservations.From", "le", date("Y-m-d", $to), "AND"],
        ["reservations.To", "ge", date("Y-m-d", $from)]
    ], "")
    ->whereGroup([
        ["reservations.From", "le", date("Y-m-d", $from), "AND"],
        ["reservations.To", "ge", date("Y-m-d", $to)]
    ], "OR")
    ->closeBracket()
    ->one();

if ($reserved) {
    echo json_encode(["status" => "error", "message" => "Номер зайнятий"]);
    exit();
}

echo json_encode(["status" => "success"]);