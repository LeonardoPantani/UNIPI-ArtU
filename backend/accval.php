<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["option"])) {
    echo "invalid_data";
    return;
}

switch($_GET["option"]) {
    case "photo": {
        echo convertArrayToString($accept_photo);
        break;
    }
    case "video": {
        echo convertArrayToString($accept_video);
        break;
    }
    case "drawing": {
        echo convertArrayToString($accept_drawing);
        break;
    }
    case "music": {
        echo convertArrayToString($accept_music);
        break;
    }
    case "text": {
        echo convertArrayToString($accept_text);
        break;
    }
    case "poetry": {
        echo convertArrayToString($accept_poetry);
        break;
    }
    case "thumbnail": {
        echo convertArrayToString($accept_thumbnail);
        break;
    }
    default: {
        echo ".error";
    }
}

function convertArrayToString($array): string
{
    foreach($array as &$value) {
        $value = "." .$value;
    }
    unset($value);

    return implode(",", $array);
}