<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if (!isset($_POST["delacc_text"])) {
    echo "error_invalid";
    return;
}

if ($username != $_POST["delacc_text"]) {
    echo "username_not_equal";
    return;
}

$stmt = $dbconn->prepare("DELETE FROM $table_users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo "error_delacc";
    return;
}

deleteAvatar($avataruri); // elimino l'avatar

deleteSession();
echo "delacc_ok";