<?php

include "../include/init.php";

Header("Content-Type: application/json");

$roomTypes = $db->find("types")->select(["ID", "Type as Name"])->rows();

if (isset($_POST["reverse"])) {
    $roomTypes = array_reverse($roomTypes);
}

echo json_encode($roomTypes);