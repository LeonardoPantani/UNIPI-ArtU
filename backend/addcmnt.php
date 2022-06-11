<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_POST["contentid"]) || !isset($_POST["text"])) {
    echo _("Dati invalidi.");
    return;
}

if(empty($_POST["text"]) || $_POST["text"] == "") {
    echo _("Non puoi inviare un commento vuoto.");
    return;
}

if(strlen($_POST["text"]) > $comment_maxlength) {
    echo _("Commento troppo lungo. Massimo " . $comment_maxlength . " caratteri.");
    return;
}

$filteredText = trim(htmlspecialchars($_POST["text"]));

$canICommentResult = canIComment($_POST["contentid"]);

if($canICommentResult == -1) {
    echo _("Non puoi accedere a questo contenuto");
    return;
}

if($canICommentResult != 0) {
    echo _("Non puoi commentare. Devi ancora attendere " . getFormattedTime($time_between_comments - $canICommentResult) . ".");
    return;
}

$currentTime = time();

$stmt = $dbconn->prepare("INSERT INTO $table_usercontent_comments (userid, contentid, text, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $id, $_POST["contentid"], $filteredText, $currentTime);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore interno.");
    return;
}

echo "addcmnt_ok";