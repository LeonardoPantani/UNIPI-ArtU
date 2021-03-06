<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["req"]) || !isset($_GET["code"])) {
    echo "error_invalid";
    return;
}

if($_GET["code"] != "accept" && $_GET["code"] != "reject") {
    echo "error_invalidcode";
    return;
}

if($_GET["code"] == "accept") {
    $dbconn->begin_transaction(); // inizio transazione

    $stmt = $dbconn->prepare("UPDATE $table_friendrequests SET status = 'accepted' WHERE id = ?");
    $stmt->bind_param("i", $_GET["req"]);
    $stmt->execute();

    if($stmt->affected_rows == 0) {
        echo "friendrequest_not_found";
        $dbconn->rollback(); // rollback
        return;
    }

    $friendrequestdata = getFriendRequestById($_GET["req"]);

    $currentTime = time();

    $stmt = $dbconn->prepare("INSERT INTO $table_friends (userida, useridb, date) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $friendrequestdata["userida"], $id, $currentTime);
    $stmt->execute();

    if($stmt->affected_rows == 0) {
        echo "error_acptfrndreq";
        $dbconn->rollback(); // rollback
        return;
    }

    $dbconn->commit();
} else {
    $stmt = $dbconn->prepare("UPDATE $table_friendrequests SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $_GET["req"]);
    $stmt->execute();

    if($stmt->affected_rows == 0) {
        echo "friendrequest_not_found";
        return;
    }
}
echo "editfrndreq_ok";