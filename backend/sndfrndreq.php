<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["userid"])) {
    echo _("Dati invalidi.");
    return;
}

if($id == $_GET["userid"]) {
    echo _("Non potete mandarvi da soli una richiesta di amicizia.");
    return;
}

if(amIFriendOf($_GET["userid"])) {
    echo _("Siete già amici.");
    return;
}

$friendreqstatus = checkFriendRequest($id, $_GET["userid"]);

if($friendreqstatus == 0) {
    echo _("C'è già una vostra richiesta di amicizia in attesa.");
    return;
}

$currentTime = time();
$status = "pending";

$stmt = $dbconn->prepare("INSERT INTO $table_friendrequests (userida, useridb, date, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $id, $_GET["userid"], $currentTime, $status);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore interno.");
    return;
}

echo "sndfrndreq_ok";