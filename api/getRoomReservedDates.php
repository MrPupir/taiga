<?php

include "../include/init.php";

Header("Content-Type: application/json");

$id = $_POST["id"];

if (!isset($id)) {
    echo json_encode(["status" => "error", "message" => "Невірні дані"]);
    exit();
}

$room = $db->find("reservations")
    ->where("RoomID", "eq", $id)
    ->where("reservations.To", "ge", date("Y-m-d"))
    ->select([
        "reservations.From",
        "reservations.To"
    ])
    ->rows();

$reserved = [];

foreach ($room as $r) {
    $from = strtotime($r["From"]);
    $to = strtotime($r["To"]);

    while ($from <= $to) {
        $reserved[] = date("Y-m-d", $from);
        $from += 86400;
    }
}

echo json_encode($reserved);