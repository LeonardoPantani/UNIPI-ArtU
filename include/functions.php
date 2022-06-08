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
 * @return int|null l"id dell'utente, null altrimenti
 */
function getUserIdByUsername($username): int|null
{
    global $dbconn, $table_users;

    if(!validateUsername($username)) return null;

    $stmt = $dbconn->prepare("SELECT $table_users.id FROM $table_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $esito = $stmt->get_result();
    $ret = $esito->fetch_assoc();

    if($ret === null || $ret === false) {
        return null;
    }

    return $ret["id"];
}

/**
 * @param id dell'utente di cui si vuole ottenere la pagina
 * @return bool|array|null l'array contenente la pagina se l'id corrisponde, null se non corrisponde niente (l'utente potrebbe non ha mai modificato la pagina o non esiste), falso in caso di errore della query
 */
function getPageById($userid): array|null
{
    global $dbconn, $table_pages, $table_users;

    $userid = intval($userid);
    if($userid == 0) return null;

    $stmt = $dbconn->prepare("SELECT $table_pages.*, $table_users.username, $table_users.avatarUri, $table_users.visibility FROM $table_pages JOIN $table_users ON $table_pages.userid = $table_users.id WHERE userid = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $esito = $stmt->get_result();
    $ret = $esito->fetch_assoc();

    if($ret === null || $ret === false) {
        return null;
    }

    return $ret;
}

/**
 * @param contentid id del contenuto di un utente
 * @return bool|array|null l'array contenente i dati del content se l'id esiste, null se non corrisponde niente, falso in caso di errore della query
 */
function getUserContentById($contentid): array|null
{
    global $dbconn, $table_usercontent, $table_users, $folder_thumbnail;

    $contentid = intval($contentid);
    if($contentid == 0) return null;

    $stmt = $dbconn->prepare("SELECT $table_usercontent.*, $table_users.username, $table_users.avatarUri FROM $table_usercontent JOIN $table_users ON $table_usercontent.userid = $table_users.id WHERE $table_usercontent.id = ?");
    $stmt->bind_param("i", $contentid);
    $stmt->execute();
    $esito = $stmt->get_result();

    $ret = $esito->fetch_assoc();
    if($ret === null || $ret === false) {
        return null;
    } else {
        $ret["tags"] = getTagArray($ret["tags"]);

        if($ret["contentExtension"] != "") {
            $ret["contentUri"] = getContentFolderByCategory($ret["type"]) . "/" . $contentid . "." . $ret["contentExtension"];
        } else {
            $ret["contentUri"] = "";
        }

        if($ret["thumbnailExtension"] != "") {
            $ret["thumbnailUri"] = $folder_thumbnail . "/" . $contentid . "." . $ret["thumbnailExtension"];
        } else {
            $ret["thumbnailUri"] = "";
        }
    }
    return $ret;
}

/**
 * Restituisce la stringa senza i caratteri "a capo".
 * @param string la stringa da cui rimuovere nuove linee
 * @return string la stringa senza nuove linee
 */
function getFixedString($string): string
{
    return trim(preg_replace('/\s+/', ' ', $string));
}

/**
 * Restituisce l'array di tags.
 * @param serializedArray array serializzato preso dal database di tags
 * @return array l'array con un tag per posizione
 */
function getTagArray($serializedArray): array
{
    $ret = @unserialize($serializedArray);

    if($ret === false) {
        return array();
    } else {
        return $ret;
    }
}

/**
 * Restituisce la stringa creata dall'array pronta per essere stampata.
 * @param array l'array da preparare per la stampa
 * @return string la stringa da stampare
 */
function getPrintableArray($array): string
{
    return implode(", ", $array);
}

function getNumPendingFriendRequests($userid) {
    global $dbconn, $table_friendrequests;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friendrequests WHERE useridb = ? AND status = 'pending'");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $esito = $stmt->get_result();

    return $esito->num_rows;
}

/**
 * @param friendreqid id della richiesta di amicizia
 * @return bool|array|null l'array contenente i dati della richiesta di amicizia se l'id esiste, null se non corrisponde niente, falso in caso di errore della query
 */
function getFriendRequestById($friendreqid): bool|array|null
{
    global $dbconn, $table_friendrequests;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friendrequests WHERE id = ?");
    $stmt->bind_param("i", $friendreqid);
    $stmt->execute();
    $esito = $stmt->get_result();

    return $esito->fetch_assoc();
}

/**
 * @param other l'id dell'utente di cui voglio sapere se sono amico
 * @return boolean vero se sono amico dell'utente con quell'id, falso altrimenti
 */
function amIFriendOf($other): bool
{
    global $dbconn, $table_friends;

    if(!isLogged()) return false;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friends WHERE (userida = ? AND useridb = ?) OR (userida = ? AND useridb = ?)");
    $stmt->bind_param("iiii", $id, $other, $other, $id);
    $stmt->execute();
    $esito = $stmt->get_result();

    if($esito->num_rows == 0) {
        return false;
    } else {
        return true;
    }
}

/**
 * @param id l'id dell'utente di cui voglio sapere info sulla richiesta di amicizia
 * @return int -1 se non è stata mai inviata, 0 se è in attesa, 1 se è stata accettata, 2 se è stata rifiutata
 */
function checkFriendRequest($me, $other): int
{
    global $dbconn, $table_friendrequests;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friendrequests WHERE userida = ? AND useridb = ? ORDER BY date DESC LIMIT 1");
    $stmt->bind_param("ii", $me, $other);
    $stmt->execute();
    $esito = $stmt->get_result();

    if($esito->num_rows == 0) {
        return -1;
    } else {
        $dati = $esito->fetch_assoc();
        if($dati["status"] === "pending") {
            return 0;
        } else if($dati["status"] == "accepted") {
            return 1;
        } else {
            return 2;
        }
    }
}

function getUserFriends($userid): bool|mysqli_result
{
    global $dbconn, $table_friends, $table_users;
    
    $query = "
    (SELECT $table_users.username as username, $table_friends.useridb as userid, $table_friends.date as date FROM friends JOIN users ON $table_friends.useridb = $table_users.id WHERE userida = ?)
    UNION
    (SELECT $table_users.username as username, $table_friends.userida as userid, $table_friends.date as date FROM friends JOIN users ON $table_friends.userida = $table_users.id WHERE useridb = ?)
    ";

    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("ii", $userid, $userid);
    $stmt->execute();

    return $stmt->get_result();
}

function getFriendsNumber($userid) {
    global $dbconn, $table_friends;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friends WHERE userida = ? OR useridb = ?");
    $stmt->bind_param("ii", $userid, $userid);
    $stmt->execute();
    $esito = $stmt->get_result();

    return $esito->num_rows;
}

function getFormattedDate($unixtime): string
{
    return date("d/m/Y", $unixtime);
}

function getFormattedDateTime($unixtime): string
{
    return date("d/m/Y H:i", $unixtime);
}

function getContentFolderByCategory($category): string
{
    global $folder_photo, $folder_video, $folder_drawing, $folder_music, $folder_text, $folder_poetry, $folder_thumbnail;
    
    switch($category) {
        case "photo": {
            return $folder_photo;
        }
        case "video": {
            return $folder_video;
        }
        case "drawing": {
            return $folder_drawing;
        }
        case "music": {
            return $folder_music;
        }
        case "text": {
            return $folder_text;
        }
        case "poetry": {
            return $folder_poetry;
        }
        case "thumbnail": {
            return $folder_thumbnail;
        }
        default: {
            return "";
        }
    }
}

function getValidExtensionsByCategory($category): array
{
    global $accept_photo, $accept_video, $accept_drawing, $accept_music, $accept_text, $accept_poetry;

    switch($category) {
        case "photo":
        {
            return $accept_photo;
        }
        case "video":
        {
            return $accept_video;
        }
        case "drawing":
        {
            return $accept_drawing;
        }
        case "music":
        {
            return $accept_music;
        }
        case "text":
        {
            return $accept_text;
        }
        case "poetry":
        {
            return $accept_poetry;
        }
        default:
        {
            return array();
        }
    }
}

function getCutString($string, $limit) {
    return (strlen($string) > $limit ? substr($string,0,$limit) . "..." : $string);
}

function getStringReadTime($string): float
{
    /*
     * In media una persona legge 200 parole al minuto (3,4 parole al secondo)
     */
    return floor(str_word_count(strip_tags($string)) / 3.4);
}


function getFormattedTime($time): string
{
    if($time < 60) {
        return gmdate('s \s\e\c\o\n\d\i', $time);
    }

    if($time < 3600) {
        return gmdate('i \m\i\n\u\t\i \e s \s\e\c\o\n\d\i', $time);
    }

    return gmdate('h \o\r\e \e i \m\i\n\u\t\i \e s \s\e\c\o\n\d\i', $time);
}

function getRatings($type, $elementID): array
{
    global $dbconn, $table_pages_ratings, $table_usercontent_ratings;

    $ret = array();
    $ret["likes"] = 0;
    $ret["dislikes"] = 0;

    if($type == "content") {
        $tabella = $table_usercontent_ratings;
        $colonna = "contentid";
    } else if($type == "page") {
        $tabella = $table_pages_ratings;
        $colonna = "userpageid";
    } else {
        return $ret;
    }
    $stmt = $dbconn->prepare("SELECT * FROM $tabella WHERE $colonna = ?");
    $stmt->bind_param("i", $elementID);
    $stmt->execute();
    $esito = $stmt->get_result();

    while($row = $esito->fetch_assoc()) {
        if($row["value"] == 0) {
            $ret["dislikes"]++;
        }

        if($row["value"] == 1) {
            $ret["likes"]++;
        }
    }

    return $ret;
}

function getUserRating($type, $userid, $elementID) {
    global $dbconn, $table_pages_ratings, $table_usercontent_ratings;

    if($type == "content") {
        $tabella = $table_usercontent_ratings;
        $colonna = "contentid";
    } else if($type == "page") {
        $tabella = $table_pages_ratings;
        $colonna = "userpageid";
    } else {
        return -1;
    }

    $stmt = $dbconn->prepare("SELECT value FROM $tabella WHERE userid = ? AND $colonna = ?");
    $stmt->bind_param("ii", $userid, $elementID);
    $stmt->execute();
    $esito = $stmt->get_result();
    $dati = $esito->fetch_assoc();
    if($dati != null) {
        return $dati["value"];
    } else {
        return -1;
    }
}

/**
 * Determina se l'utente attualmente connesso può accedere al contenuto o meno.
 * @param contentid id del contenuto dell'utente a cui si vuole accedere
 * @return bool vero se si può interagire con il contenuto, falso altrimenti
 */
function canISeeContent($contentid): bool
{
    global $id;

    $dati = getUserContentById($contentid);
    if($dati != null) {
        if($dati["private"] == "0" || (isLogged() && (amIFriendOf($dati["userid"]) || $id == $dati["userid"]))) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Determina se l'utente attualmente connesso può accedere alla pagina o meno.
 * @param userpageid id della pagina dell'utente a cui si vuole accedere (
 * @return bool vero se si può interagire con la pagina, falso altrimenti
 */
function canISeePage($userpageid): bool
{
    global $id;

    $dati = getPageById($userpageid);
    if($dati != null) {
        if($dati["visibility"] == 1 || (isLogged() && (amIFriendOf($dati["userid"]) || $id == $dati["userid"]))) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
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
