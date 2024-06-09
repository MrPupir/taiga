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

if (isset($_POST["oper"])) {
    if ($_POST["oper"] == "del") {
        $result = $db->delete("reservations", "ID=" . $_POST["id"]);
        if ($result === true) {
            echo json_encode(["success" => true]);
        } else {
            header("HTTP/1.1 422 Can not delete");
            echo json_encode(["success" => false]);
        }
    }
} else {
    header("HTTP/1.1 400 Bad params");
    echo json_encode(["success" => false]);
}
?>
