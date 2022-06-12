<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if(!isset($_GET["value"]) || !isset($_GET["elementid"]) || !isset($_GET["type"])) {
    echo _("Dati invalidi.");
    return;
}

if($_GET["value"] != "like" && $_GET["value"] != "dislike") {
    echo _("Valore rating non valido.");
    return;
}

if($_GET["type"] != "content" && $_GET["type"] != "page") {
    echo _("Tipo rating non valido.");
    return;
}

if($_GET["type"] == "content") {
    $tabella = $table_usercontent_ratings;
    $colonna = "contentid";
} else {
    $tabella = $table_pages_ratings;
    $colonna = "userpageid";
}

// verifica che l'utente possa effettuare modifica al rating
if(($_GET["type"] == "content" && !canISeeContent($_GET["elementid"])) || ($_GET["type"] == "page" && !canISeePage($_GET["elementid"]))) {
    echo _("Nessun permesso per effettuare questa modifica.");
    return;
}

// inizio logica
$previousRating = getUserRating($id, $_GET["type"], $_GET["elementid"]);
if($_GET["value"] == "like") {
    $newRating = 1; // valore like
} else {
    $newRating = 0; // valore dislike
}

if($newRating == $previousRating) {
    $stmt = $dbconn->prepare("DELETE FROM $tabella WHERE userid = ? AND $colonna = ?");
    $stmt->bind_param("ii", $id, $_GET["elementid"]);
} else {
    // inserisco il rating, altrimenti metto l'opposto di quello che c'è già
    $stmt = $dbconn->prepare("INSERT INTO $tabella (userid, $colonna , value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE value = ?");
    $stmt->bind_param("iiii", $id, $_GET["elementid"], $newRating, $newRating);
}
$stmt->execute();
if($stmt->affected_rows != 1 && $stmt->affected_rows != 2) { // se inserisco e basta 1, se aggiorno 2
    echo _("Errore interno.");
    return;
}

echo "chngrtng_ok:" . $newRating . ":" . $previousRating;