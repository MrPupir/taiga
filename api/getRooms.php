<?php

include "../include/init.php";

Header("Content-Type: application/json");

$pagination = new Pagination();
$pagination->setLimit((isset($_REQUEST["rows"])) ? $_REQUEST["rows"] : 4);

$rooms = $db->find("rooms");

if (isset($_POST["filter"])) {
    if (!isset($_POST["type"])) {
        echo json_encode([
            "rows" => [],
            "page" => 1,
            "records" => 0,
            "total" => 0,
            "sql" => $rooms->sql(),
        ]);
        exit();
    }

    $type = $_POST["type"];

    if ($type != "all") {
        $rooms->where("RoomType", "IN", $type);
    }

    $price = $_POST["price"];

    if ($price[0] != 0 || $price[1] != 10000) {
        $rooms->where("Price", ">=", $price[0]);
        $rooms->where("Price", "<=", $price[1]);
    }

    $adults = $_POST["adults"];

    if ($adults != 1) {
        $rooms->where("DoubleBeds", ">=", $adults / 2);
    }

    $children = $_POST["children"];

    if ($children != 0) {
        $rooms->where("SingleBeds", ">=", $children);
    }
}

$count = $rooms->count();
$pagination->setRowsCount($count);

$pagination->setPage((isset($_REQUEST["page"])) ? $_REQUEST["page"] : 1);
$rooms->offset($pagination->getFirst());
$rooms->limit($pagination->getLimit());

$rooms = $rooms->leftJoin("types", [
    ["field" => "`rooms`.`RoomType`", "condition" => "=", "value" => "`types`.`ID`"]
])->select([
    "rooms.ID",
    "rooms.RoomName as Name",
    "rooms.Price",
    "rooms.RoomCount",
    "rooms.DoubleBeds",
    "rooms.SingleBeds",
    "rooms.RoomType",
    "types.Type as RoomTypeName",
    "types.Benefits as Benefits",
]);

$response = [
    "rows" => [],
    "page" => $pagination->getPage(),
    "records" => $pagination->getRowsCount(),
    "total" => $pagination->getPageCount(),
    "sql" => $rooms->sql(),
];

$rooms = $rooms->rows();

$r = [];

foreach($rooms as $room) {
    $r[] = [
        "ID" => $room["ID"],
        "Name" => $room["Name"],
        "Price" => $room["Price"],
        "Rooms" => $room["RoomCount"],
        "Double" => $room["DoubleBeds"],
        "Single" => $room["SingleBeds"],
        "RoomType" => $room["RoomType"],
        "Images" => $db->find("images")->where("roomID", "=", $room["ID"])->select("ID")->rows(),
        "Type" => $room["RoomTypeName"],
        "Benefits" => json_decode($room["Benefits"]),
    ];
}

foreach ($r as $key => $room) {
    $r[$key]["Images"] = $db->find("images")->where("roomID", "=", $room["ID"])->select("ID")->rows();
}

$response["rows"] = $r;

echo json_encode($response);
