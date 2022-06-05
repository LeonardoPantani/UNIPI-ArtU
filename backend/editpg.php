<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_POST["htmeditor"])) {
    echo _("Dati invalidi.");
    return;
}

if(strlen($_POST["htmeditor"]) > $content_page_maxlength) {
    echo _("Pagina troppo lunga. Il numero massimo di caratteri è " . $content_page_maxlength);
    return;
}

// aggiorno la pagina nel database
$query = "INSERT INTO $table_pages (userid, content, editDate) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE content = ?, editDate = ?";

$currentTime = time();
$content = htmlspecialchars($_POST["htmeditor"]);

$stmt = $dbconn->prepare($query);
$stmt->bind_param("isisi", $id, $content, $currentTime, $content, $currentTime);
$stmt->execute();

if ($stmt->affected_rows == 0) {
    echo _("Errore interno durante l'aggiornamento della pagina.");
    return;
}

echo "editpg_ok";