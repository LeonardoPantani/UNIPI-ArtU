<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if (!isset($_POST["delacc_text"])) {
    echo _("Dati invalidi.");
    return;
}

if ($username != $_POST["delacc_text"]) {
    echo _("Nome utente errato.");
    return;
}

deleteUserContentFiles($id);

$stmt = $dbconn->prepare("DELETE FROM $table_users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore interno.");
    return;
}

deleteAvatarFile($avataruri); // elimino l'avatar

deleteSession();
echo "delacc_ok";