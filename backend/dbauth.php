<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

if (isset($_POST["access"])) { // è un login
    if (!isset($_POST["password"])) {
        echo "error_invalid";
        return;
    }
    $username = $_POST["access"];
    $password = $_POST["password"];

    if (!checkLogin($username, $password)) {
        return;
    }

    $stmt = $dbconn->prepare("SELECT * FROM $table_users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $esito = $stmt->get_result();
    $datiUtente = $esito->fetch_assoc();
    $nrighe = $esito->num_rows;

    if ($nrighe == 0) {
        echo "wrong_access";
        return;
    }

    $verificaPassword = password_verify($password, $datiUtente["password"]);
    if (!$verificaPassword) {
        echo "wrong_password";
        return;
    }

    session_write_close();
    session_name('__Secure-Session');
    session_start();

    $_SESSION["id"] = $datiUtente["id"];
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $datiUtente["email"];
    $_SESSION["creationDate"] = $datiUtente["creationDate"];
    $_SESSION["visibility"] = $datiUtente["visibility"];

    if($datiUtente["avatarUri"] == "") {
        $_SESSION["avatarUri"] = $defaultavatar_file;
    } else {
        $_SESSION["avatarUri"] = $datiUtente["avatarUri"];
    }

    echo "login_ok";
} else { // è una registrazione
    if (!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["repeatpassword"])) {
        echo "error_invalid";
        return;
    }
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeatpassword = $_POST["repeatpassword"];
    $currentTime = time();

    if (!checkRegister($username, $email, $password, $repeatpassword)) {
        return;
    }

    $password = password_hash($password, PASSWORD_DEFAULT); //hash password

    session_write_close();
    session_name('__Secure-Session');
    session_start();

    $stmt = $dbconn->prepare("INSERT INTO $table_users (username, email, password, creationDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $password, $currentTime);
    $stmt->execute();

    if ($stmt->affected_rows != 1) {
        echo "error_registration";
        return;
    }

    $_SESSION["id"] = $stmt->insert_id;
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    $_SESSION["creationDate"] = $currentTime;
    $_SESSION["visibility"] = 1;
    $_SESSION["avatarUri"] = $defaultavatar_file;

    echo "register_ok";
}

function checkLogin($username, $password): bool
{
    if (!validateUsername($username)) {
        echo "invalid_username";
        return false;
    }

    if(!validatePassword($password)) {
        echo "short_password";
        return false;
    }

    return true;
}

function checkRegister($username, $email, $password, $repeatpassword): bool
{
    if (!checkLogin($username, $password)) return false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "invalid_email";
        return false;
    }

    if ($password != $repeatpassword) {
        echo "passwords_not_equal";
        return false;
    }

    global $dbconn;
    global $table_users;

    $stmt = $dbconn->prepare("SELECT * FROM $table_users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $esito = $stmt->get_result();
    if($esito->num_rows != 0) {
        echo "access_already_exists";
        return false;
    }

    return true;
}
