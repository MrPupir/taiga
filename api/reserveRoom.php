<?php

include "../include/init.php";

Header("Content-Type: application/json");

$from = $_POST["from"];
$to = $_POST["to"];
$id = $_POST["id"];
$adults = $_POST["adults"];
$children = $_POST["children"];
$email = $_POST["email"];
$surname = $_POST["surname"];
$name = $_POST["name"];
$phone = $_POST["phone"];
$food = $_POST["food"];

if (!isset($from) || !isset($to) || !isset($id) || !isset($adults) || !isset($children) || !isset($email) || !isset($surname) || !isset($name) || !isset($phone) || !isset($food)) {
    echo json_encode(["status" => "error", "message" => "Невірні дані"]);
    exit();
}

$room = $db->find("rooms")->where("ID", "=", $id)->one();

if (!$room) {
    echo json_encode(["status" => "error", "message" => "Номер не знайдено"]);
    exit();
}

$from = str_replace("/", "-", $from);
$from = strtotime($from);

if ($from == NULL) {
    echo json_encode(["status" => "error", "message" => "Невірна дата"]);
    exit();
}

if ($from < time()) {
    echo json_encode(["status" => "error", "message" => "Невірна дата"]);
    exit();
}

$to = str_replace("/", "-", $to);
$to = strtotime($to);

if ($to == NULL) {
    echo json_encode(["status" => "error", "message" => "Невірна дата"]);
    exit();
}

if ($to < $from) {
    echo json_encode(["status" => "error", "message" => "Невірна дата"]);
    exit();
}

if ($to > $from + 1296000) {
    echo json_encode(["status" => "error", "message" => "Невірна дата"]);
    exit();
}

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
    echo json_encode(["status" => "error", "message" => "На вибрану дату номер зайнятий! Оберіть іншу дату."]);
    exit();
}

$days = ceil(($to - $from) / 86400);

$price = $room["Price"] * $days;

$food = $food === "true" ? 1 : 0;

if ($food) {
    $price += 100 * $adults * $days;
}

$price = number_format($price, 2, ".", "");

$roomID = $db->insert("reservations", [
    "RoomID" => $id,
    "From" => date("Y-m-d", $from),
    "To" => date("Y-m-d", $to),
    "Adults" => $adults,
    "Children" => $children,
    "Email" => $email,
    "Surname" => $surname,
    "Name" => $name,
    "Phone" => $phone,
    "Food" => $food,
    "Price" => $price,
]);

$roomName = $room["RoomName"];

echo json_encode(["status" => "success", "message" => "Бронювання номеру «" . $roomName . "» з " . date("d/m/Y", $from) . " по " . date("d/m/Y", $to) . " успішне! Очікуйте дзвінка від адміністратора.", "id" => $roomID]);