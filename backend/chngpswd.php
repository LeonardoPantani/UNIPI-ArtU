<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

if (!isset($_POST["oldpassword"]) || !isset($_POST["newpassword"])) {
    echo "error_invalid";
    return;
}

// verifico che la nuova password rispetti i criteri
if (strlen($_POST["newpassword"]) < 6) {
    echo "short_password";
    return;
}
$newpassword = password_hash($_POST["newpassword"], PASSWORD_DEFAULT); //hash password

// verifico che la vecchia password corrisponda a quella nel database
$stmt = $dbconn->prepare("SELECT password FROM $table_users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$esito = $stmt->get_result();
$datiUtente = $esito->fetch_assoc();
$nrighe = $esito->num_rows;

if ($nrighe == 0) {
    echo "error_nouser";
    return;
}

$verificaPassword = password_verify($_POST["oldpassword"], $datiUtente["password"]);
if (!$verificaPassword) {
    echo "wrong_old_password";
    return;
}

// aggiorno la password nel database
$stmt = $dbconn->prepare("UPDATE $table_users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $newpassword, $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo "error_chngpswd";
    return;
}

echo "chngpswd_ok";
