<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["req"])) {
    echo "error_invalid";
    return;
}

$stmt = $dbconn->prepare("UPDATE $table_friendrequests SET status = 'rejected' WHERE id = ?");
$stmt->bind_param("i", $_GET["req"]);
$stmt->execute();

if($stmt->affected_rows == 0) {
    echo "friendrequest_not_found";
    return;
}

echo "rejtfrndreq_ok";