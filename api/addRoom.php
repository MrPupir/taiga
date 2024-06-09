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

$images = $_POST["images"];
$type = $_POST["type"];
$name = $_POST["name"];
$price = $_POST["price"];
$rooms = $_POST["rooms"];
$double = $_POST["double"];
$single = $_POST["single"];

$roomType = $db->find("types")->where("ID", "=", $type)->one();

if (!$roomType) {
    echo json_encode(["success" => false, "message" => "Невірний тип номеру"]);
    exit();
}

if (empty($name)) {
    echo json_encode(["success" => false, "message" => "Введіть назву номеру"]);
    exit();
}

if (!is_numeric($price) || $price <= 0 || $price < 1000 || $price > 10000) {
    echo json_encode(["success" => false, "message" => "Невірна ціна"]);
    exit();
}

if (!is_numeric($rooms) || $rooms <= 0 || $rooms < 1 || $rooms > 3) {
    echo json_encode(["success" => false, "message" => "Невірна кількість кімнат"]);
    exit();
}

if (!is_numeric($double) || $double <= 0 || $double < 1 || $double > 3) {
    echo json_encode(["success" => false, "message" => "Невірна кількість двоспальних ліжок"]);
    exit();
}

if (!is_numeric($single) || $single < 0 || $single > 4) {
    echo json_encode(["success" => false, "message" => "Невірна кількість односпальних ліжок"]);
    exit();
}

if (count($images) < 1 || count($images) > 10) {
    echo json_encode(["success" => false, "message" => "Невірна кількість зображень"]);
    exit();
}

$roomID = $db->insert("rooms", [
    "RoomType" => $type,
    "RoomName" => $name,
    "Price" => $price,
    "RoomCount" => $rooms,
    "DoubleBeds" => $double,
    "SingleBeds" => $single
]);

foreach ($images as $image) {
    $image = str_replace("data:image/png;base64,", "", $image);
    $image = str_replace("data:image/jpeg;base64,", "", $image);
    $image = str_replace(" ", "+", $image);
    $image = base64_decode($image);

    $imageID = $db->insert("images", [
        "RoomID" => $roomID
    ]);

    file_put_contents("../uploads/" . $imageID . "_tmp.png", $image);

    $im = imageCreateFromAny("../uploads/" . $imageID . "_tmp.png");
    list($width, $height) = getimagesize("../uploads/" . $imageID . "_tmp.png");

    $original_stamp = imagecreatefrompng("../images/watermark.png");
    list($original_sx, $original_sy) = getimagesize("../images/watermark.png");

    $scale_factor = min($width / (4 * $original_sx), $height / (4 * $original_sy));

    $new_sx = intval($original_sx * $scale_factor);
    $new_sy = intval($original_sy * $scale_factor);

    $resized_stamp = imagecreatetruecolor($new_sx, $new_sy);

    imagealphablending($resized_stamp, false);
    imagesavealpha($resized_stamp, true);

    imagecopyresampled($resized_stamp, $original_stamp, 0, 0, 0, 0, $new_sx, $new_sy, $original_sx, $original_sy);

    imagecopy($im, $resized_stamp, intval(($width - $new_sx) / 2), intval(($height - $new_sy) / 2), 0, 0, $new_sx, $new_sy);

    imagepng($im, "../uploads/" . $imageID . ".png");
    imagedestroy($im);
    imagedestroy($resized_stamp);
    imagedestroy($original_stamp);

    unlink("../uploads/" . $imageID . "_tmp.png");
}

echo json_encode(["success" => true, "message" => "Номер успішно доданий"]);