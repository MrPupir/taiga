<?php

include "../include/init.php";

Header("Content-Type: application/json");

if (!isset($_SESSION["currentAccount"])) {
    echo json_encode(["success" => false, "message" => "Ви не авторизовані"]);
    exit();
}

if (hasFlag("a") === false) {
    echo json_encode(["success" => false, "message" => "У вас немає прав доступу"]);
    exit();
}

$pagination = new Pagination();
$pagination->setLimit((isset($_POST["rows"])) ? $_POST["rows"] : 10);

$builder = $db->find("reservations")
    ->leftJoin("rooms", [
        ["field" => "`rooms`.`ID`", "condition" => "=", "value" => "`reservations`.`RoomID`"],
    ]);

$builderCount = $db->find("reservations")
    ->leftJoin("rooms", [
        ["field" => "`rooms`.`ID`", "condition" => "=", "value" => "`reservations`.`RoomID`"],
    ]);

$fields = [
    "ID" => "reservations.ID",
    "Name" => "reservations.Name",
    "Phone" => "reservations.Phone",
    "CheckIn" => "reservations.From",
    "CheckOut" => "reservations.To",
    "Adults" => "reservations.Adults",
    "Children" => "reservations.Children",
    "RoomName" => "rooms.RoomName",
    "Food" => "reservations.Food",
    "Price" => "reservations.Price",
];

if (isset($_POST["_search"]) && $_POST["_search"] === "true") {
    if ($_POST["filters"]) {
        $filters = json_decode($_POST["filters"], true);
        foreach ($filters["rules"] as $f) {
            if (trim($f["data"]) === "") {
                continue;
            }

            $builder->where($fields[$f["field"]], $f["op"], $f["data"]);
            $builderCount->where($fields[$f["field"]], $f["op"], $f["data"]);
        }
    } else {
        $builder->where($fields[$_POST["searchField"]], $_POST["searchOper"], $_POST["searchString"]);
        $builderCount->where($fields[$_POST["searchField"]], $_POST["searchOper"], $_POST["searchString"]);
    }
}

$builder->orderby(((isset($_REQUEST["sidx"])) ? $fields[$_REQUEST["sidx"]] : "reservations.ID") . " " . ((isset($_REQUEST["sord"])) ? $_REQUEST["sord"] : "asc"));

$c = $builderCount->count();
$pagination->setRowsCount($c);

$pagination->setPage((isset($_REQUEST["page"])) ? $_REQUEST["page"] : 1);
$builder->offset($pagination->getFirst());
$builder->limit($pagination->getLimit());

$rooms = $builder->select([
    "reservations.ID as RID",
    "reservations.Name",
    "reservations.Phone",
    "reservations.From",
    "reservations.To",
    "reservations.Adults",
    "reservations.Children",
    "rooms.RoomName",
    "reservations.Food",
    "reservations.Price",
])->rows();

$response = [
    "rows" => [],
    "page" => $pagination->getPage(),
    "records" => $pagination->getRowsCount(),
    "total" => $pagination->getPageCount(),
    "sql" => $builder->sql(),
];

foreach ($rooms as $room) {
    $response["rows"][] = [
        "id" => $room["RID"],
        "cell" => [
            $room["RID"],
            $room["Name"],
            $room["Phone"],
            $room["From"],
            $room["To"],
            $room["Adults"],
            $room["Children"],
            $room["RoomName"],
            $room["Food"],
            $room["Price"],
        ],
    ];
}

echo json_encode($response);
