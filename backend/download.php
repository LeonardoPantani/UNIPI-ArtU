<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

if (!isset($_GET["id"])) {
    echo _("Dati invalidi.");
    return;
}

$usercontent = getContentById($_GET["id"]);

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=" . $usercontent["username"] . "_" . $usercontent["id"] . "_" . time() . "."  . $usercontent["contentExtension"]);
readfile("../" . $usercontent["contentUri"]);