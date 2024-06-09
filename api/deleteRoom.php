<?php

include "../include/init.php";

Header("Content-Type: application/json");

if (!isset($_SESSION["currentAccount"])) {
    echo json_encode(["success" => false, "message" => "Ви не авторизовані"]);
    exit();
}

if (hasFlag("b") === false) {
    echo json_encode(["success" => false, "message" => "У вас немає прав доступу"]);
    exit();
}

$roomID = $_POST["roomID"];

$room = $db->find("rooms")->where("ID", "=", $roomID)->one();

if (!$room) {
    echo json_encode(["success" => false, "message" => "Номер не знайдено"]);
    exit();
}

$images = $db->find("images")->where("roomID", "=", $roomID)->rows();

foreach ($images as $image) {
    $db->delete("images", "ID = " . $image["ID"]);

    if (file_exists("../uploads/" . $image["ID"] . ".png")) {
        unlink("../uploads/" . $image["ID"] . ".png");
    }
}

$db->delete("reservations", "RoomID = " . $roomID);
$db->delete("rooms", "ID = " . $roomID);

echo json_encode(["success" => true, "message" => "Номер видалено"]);