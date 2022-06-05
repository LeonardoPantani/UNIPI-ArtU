<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(isset($_GET["deleteimg"]) && $_GET["deleteimg"] == "1") {
    // rimuovo l'url dell'immagine dal database
    $stmt = $dbconn->prepare("UPDATE $table_users SET avatarUri = NULL WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    deleteAvatar($avataruri);

    $_SESSION["avatarUri"] = $defaultavatar_file;
    $avataruri = $defaultavatar_file;

    return;
}

if(!isset($_FILES["avatarimginput"])) {
    echo _("Dati invalidi.");
    return;
}

$imagename = $_FILES["avatarimginput"]["name"]; // nome immagine nel pc dell'utente
$imageextension = pathinfo($imagename, PATHINFO_EXTENSION); // estensione immagine
$imagesize = $_FILES["avatarimginput"]["size"]; // dimensione in byte
$imageerror = $_FILES["avatarimginput"]["error"]; // errore upload immagine
$imagetemp = $_FILES["avatarimginput"]["tmp_name"]; // nome temporaneo immagine caricata

if (!is_uploaded_file($imagetemp)) {
    echo _("Upload fallito");
    return;
}

$filename =  $id . "." . $imageextension;

if($filename != $avataruri) { // se cambia il nome del file devo eliminare quello vecchio
    deleteAvatar($avataruri);
}

if (!move_uploaded_file($imagetemp, "../" . $folder_avatars . "/" . $filename)) {
    echo _("Errore interno");
    return;
}

// aggiorno l'url dell'immagine nel database
$stmt = $dbconn->prepare("UPDATE $table_users SET avatarUri = ? WHERE id = ?");
$stmt->bind_param("si", $filename, $id);
$stmt->execute();

$_SESSION["avatarUri"] = $filename;
$avataruri = $filename;

echo "chngimg_ok";
