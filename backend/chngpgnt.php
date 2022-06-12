<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if (!isset($_POST["chngpgnt_numElemsPerPage"])) {
    echo _("Dati invalidi.");
    return;
}

if(!in_array($_POST["chngpgnt_numElemsPerPage"], $validPaginationNumbers)) {
    echo _("Numero di elementi non valido.");
    return;
}

$stmt = $dbconn->prepare("UPDATE $table_users SET setting_numElemsPerPage = ? WHERE id = ?");
$stmt->bind_param("ii", $_POST["chngpgnt_numElemsPerPage"], $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore interno durante la modifica del numero di elementi.");
    return;
}

$_SESSION["setting_numElemsPerPage"] = $_POST["chngpgnt_numElemsPerPage"];
$setting_numElemsPerPage = $_POST["chngpgnt_numElemsPerPage"];

echo "chngpgnt_ok";