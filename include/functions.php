<?php
header('X-Frame-Options: sameorigin');
header('X-Content-Type-Options: nosniff');
header("X-XSS-Protection: 1; mode=block");
header_remove("X-Powered-By");

ini_set("session.cookie_httponly", 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set("session.gc_maxlifetime", 3600); // 1 ora prima della scadenza della sessione
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
ini_set("log_errors", 1);
ini_set("error_log", "include/error_log.log");

session_set_cookie_params([
    'lifetime' => 10 * 60 * 1000, // 10 minuti
    'path' => '/',
    'domain' => "",
    'secure' => true,
    'httponly' => true,
    'samesite' => 'none'
]);

session_name('__Secure-Session');
session_start();

function isLogged(): bool
{
    if (isset($_SESSION["id"])) {
        return true;
    } else {
        return false;
    }
}

function kickGuestUser($ajax = false, $redirect = "./auth.php")
{
    if (!isLogged()) {
        if ($ajax) {
            echo "error_unlogged";
        } else {
            header("Location:" . $redirect);
        }
        exit;
    }
}

function kickLoggedUser($ajax = false, $redirect = "./")
{
    if (isLogged()) {
        if ($ajax) {
            echo "error_logged";
        } else {
            header("Location:" . $redirect);
        }
        exit;
    }
}

function deleteSession($redirect = "")
{
    session_destroy();

    if ($redirect != "") {
        header("Location:./" . $redirect);
    }
}

function validateUsername($username): bool
{
    // accettati: lettere, numeri e trattini bassi | lunghezza minima: 6 caratteri | \w è uguale a "[0-9A-Za-z_]"
    if (!preg_match('/^\w{6,}$/', $username)) return false;

    return true;
}

function validatePassword($password): bool
{
    if (strlen($password) < 6) return false;

    return true;
}

function deleteAvatar($avataruri)
{
    global $defaultavatar_file, $folder_avatars;
    if($avataruri == $defaultavatar_file) return;

    if (file_exists("../" . $folder_avatars . "/" . $avataruri)) {
        unlink("../" . $folder_avatars . "/" . $avataruri);
    }
}

/**
 * @param username di cui si vuole ottenere i dati
 * @return bool|array|null l'array contenente i dati dell'utente se l'username corrisponde, null se non corrisponde niente, falso in caso di errore della query
 */
function getUserDataByUsername($username): bool|array|null
{
    global $dbconn, $table_users;

    $stmt = $dbconn->prepare("SELECT id,username,email,creationDate,visibility,avatarUri FROM $table_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $esito = $stmt->get_result();

    return $esito->fetch_assoc();
}

function getFormattedDate($unixtime): string
{
    return date("d/m/Y", $unixtime);
}


// -------- CODICE GLOBALE
if (isLogged()) {
    $id = $_SESSION["id"];
    $username = $_SESSION["username"];
    $email = $_SESSION["email"];
    $creationDate = $_SESSION["creationDate"];
    $visibility = $_SESSION["visibility"];
    $avataruri = $_SESSION["avatarUri"];
}


$url = str_replace(".php", "", explode('/', $_SERVER["PHP_SELF"]));
$pagename = end($url);
