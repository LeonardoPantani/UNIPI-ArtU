<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["req"])) {
    echo "error_invalid";
    return;
}