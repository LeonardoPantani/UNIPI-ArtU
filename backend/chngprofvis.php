<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if (!isset($_POST["chngprofvis_text"])) {
    echo "error_invalid";
    return;
}

if ($username != $_POST["chngprofvis_text"]) {
    echo "username_not_equal";
    return;
}

$stmt = $dbconn->prepare("UPDATE $table_users SET visibility = NOT visibility WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo "error_chngprofvis";
    return;
}

$_SESSION["visibility"] = !$visibility;
$visibility = $_SESSION["visibility"];
echo "chngprofvis_ok";
