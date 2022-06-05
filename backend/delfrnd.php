<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["userid"])) {
    echo _("Dati invalidi.");
    return;
}

$stmt = $dbconn->prepare("DELETE FROM $table_friends WHERE (userida = ? AND useridb = ?) OR (userida = ? AND useridb = ?)");
$stmt->bind_param("iiii", $id, $_GET["userid"], $_GET["userid"], $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore durante la rimozione.");
    return;
}

echo "delfrnd_ok";