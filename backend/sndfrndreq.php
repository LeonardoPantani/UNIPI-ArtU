<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["userid"])) {
    echo "error_invalid";
    return;
}

if($id == $_GET["userid"]) {
    echo "same_user";
    return;
}

if(amIFriendOf($_GET["userid"])) {
    echo "already_friends";
    return;
}

$friendreqstatus = checkFriendRequest($_GET["userid"]);
if($friendreqstatus == 0) {
    echo "already_sent";
    return;
}

$currentTime = time();
$status = "pending";

$stmt = $dbconn->prepare("INSERT INTO $table_friendrequests (userida, useridb, date, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $id, $_GET["userid"], $currentTime, $status);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo "error_sndfrndreq";
    return;
}

echo "sndfrndreq_ok";