<?php

function imageCreateFromAny($filepath)
{
    $type = exif_imagetype($filepath);

    $allowedTypes = array(
        1,
        2,
        3,
        6,
    );

    if (!in_array($type, $allowedTypes)) {
        return false;
    }

    switch ($type) {
        case 1:
            $im = imageCreateFromGif($filepath);
            break;
        case 2:
            $im = imageCreateFromJpeg($filepath);
            break;
        case 3:
            $im = imageCreateFromPng($filepath);
            break;
        case 6:
            $im = imageCreateFromBmp($filepath);
            break;
    }

    return $im;
}

function hasFlag($flag)
{
    if (!isset($_SESSION["currentAccount"])) {
        return false;
    }

    if (!isset($_SESSION["currentAccount"]["Flags"])) {
        return false;
    }

    if (strpos($_SESSION["currentAccount"]["Flags"], $flag) === false) {
        return false;
    }

    return true;
}