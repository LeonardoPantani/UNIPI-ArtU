<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["id"])) {
    echo _("Dati invalidi.");
    return;
}

$stmt = $dbconn->prepare("DELETE FROM $table_usercontent_comments WHERE id = ? AND userid = ?");
$stmt->bind_param("ii", $_GET["id"], $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Impossibile eliminare il commento.");
    return;
}

echo "delcmnt_ok";