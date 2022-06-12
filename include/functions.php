<?php
ini_set("session.cookie_httponly", 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set("session.gc_maxlifetime", 3600); // 1 ora prima della scadenza della sessione
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 1);
ini_set("log_errors", 1);
ini_set("error_log", "include/error_log.log");
ini_set("error_reporting", $error_reporting);

session_set_cookie_params([ // imposto che la sessione duri tot tempo
    'lifetime' => 10 * 60 * 1000, // 10 minuti
    'path' => '/',
    'domain' => "",
    'secure' => true,
    'httponly' => true,
    'samesite' => 'none'
]);

session_start();

/**
 * @return bool vero se l'utente è loggato, falso altrimenti
 */
function isLogged(): bool
{
    if (isset($_SESSION["id"])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Espelle / mostra messaggio di errore agli utenti non loggati
 * @param ajax se vero viene stampato il messaggio e non si viene reinderizzati
 * @param redirect la pagina di reindirizzamento
 */
function kickGuestUser($ajax = false, $redirect = "./auth.php")
{
    if (!isLogged()) {
        if ($ajax) {
            echo _("Non hai effettuato il login.");
        } else {
            header("Location:" . $redirect);
        }
        exit;
    }
}

/**
 * Espelle / mostra messaggio di errore agli utenti loggati
 * @param ajax se vero viene stampato il messaggio e non si viene reinderizzati
 * @param redirect la pagina di reindirizzamento
 */
function kickLoggedUser($ajax = false, $redirect = "./")
{
    if (isLogged()) {
        if ($ajax) {
            echo _("Hai già effettuato il login.");
        } else {
            header("Location:" . $redirect);
        }
        exit;
    }
}

/**
 * Elimina la sessione attuale.
 * @param redirect la pagina di reindirizzamento, "" se non si vuole reindirizzare l'utente
 */
function deleteSession($redirect = "")
{
    session_destroy();

    if ($redirect != "") {
        header("Location:./" . $redirect);
    }
}

/**
 * Valid l'username seguendo la regex del config
 * @param username l'username da validare
 * @return bool vero se l'username è valido, falso altrimenti
 */
function validateUsername($username): bool
{
    global $username_regex;

    // accettati: lettere, numeri e trattini bassi | lunghezza accettata: tra 6 e 20 caratteri compresi | \w è uguale a "[0-9A-Za-z_]"
    if (!preg_match('/'. $username_regex .'/', $username)) return false;

    return true;
}

/**
 * Valid la password seguendo le specifiche del config.
 * @param username la password da validare
 * @return bool vero se la password è valida, falso altrimenti
 */
function validatePassword($password): bool
{
    global $password_minlength;

    if (strlen($password) < $password_minlength) return false;

    return true;
}

/**
 * Elimina il file specificato dal parametro
 * @param avataruri l'uri dell'avatar da cancellare
 */
function deleteAvatarFile($avataruri)
{
    global $defaultavatar_file, $folder_avatars;
    if($avataruri == $defaultavatar_file) return;

    $toDelete = "../" . $folder_avatars . "/" . $avataruri;

    if (file_exists($toDelete)) {
        unlink($toDelete);
    }
}

/**
 * Elimina tutti i contenuti dell'utente dal disco.
 * NOTA: non cancella gli elementi nel database! Fare una query.
 * @param userid l'id dell'utente di cui cancellare i contenuti da disco
 */
function deleteUserContentFiles($userid) {
    global $dbconn, $table_usercontent, $folder_thumbnail;

    $stmt = $dbconn->prepare("SELECT $table_usercontent.* FROM $table_usercontent WHERE userid = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $esito = $stmt->get_result();

    while($row = $esito->fetch_assoc()) {
        $toDelete = "../" . getContentFolderByCategory($row["type"]) . "/" . $row["id"] . "." . $row["contentExtension"];

        if(file_exists($toDelete)) {
            unlink($toDelete);
        }

        if($row["thumbnailExtension"] != "") { // c'è una thumbnail
            $toDeleteThumbnail = "../" . $folder_thumbnail . "/" . $row["id"] . "." . $row["thumbnailExtension"];

            if(file_exists($toDeleteThumbnail)) {
                unlink($toDeleteThumbnail);
            }
        }
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
 * Fornisce i dati sulla pagina dell'utente.
 * @param userid l'id dell'utente di cui ottenere i dati sulla pagina
 * @return array|null l'array associativo con gli attributi della pagina, null se l'utente non ha mai modificato la propria pagina (o se l'utente non esiste)
 */
function getUserPageById($userid): array|null {
    global $dbconn, $table_users, $table_pages;

    $stmt = $dbconn->prepare("SELECT $table_users.*, $table_pages.* FROM $table_users LEFT JOIN $table_pages ON $table_users.id = $table_pages.userid WHERE $table_users.id = ?");
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
function getContentById($contentid): array|null
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
 * @param userid l'id dell'utente di cui avere i contenuti creati
 * @return bool|array|null l'array contenente i contenuti dell'utente se l'id esiste, null se non corrisponde niente, falso in caso di errore della query
 */
function getUserContent($userid, $private = 0, $limit = 0): mysqli_result|null
{
    global $dbconn, $table_usercontent, $table_users;

    $userid = intval($userid);
    if($userid == 0) return null;

    if($limit == 0) $limitText = ""; else $limitText = "LIMIT $limit";

    $stmt = $dbconn->prepare("SELECT $table_usercontent.*, $table_users.username, $table_users.avatarUri FROM $table_usercontent JOIN $table_users ON $table_usercontent.userid = $table_users.id WHERE $table_usercontent.private = ? AND $table_users.id = ? ORDER BY $table_usercontent.creationDate DESC $limitText");
    $stmt->bind_param("ii", $private, $userid);
    $stmt->execute();
    return $stmt->get_result();
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
    global $id, $dbconn, $table_friends;

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

/**
 * Fornisce la lista di amici dell'utente.
 * @param userid l'id dell'utente di cui ottenere la lista di amici
 * @return bool|mysqli_result il result su cui fare fetch_assoc()
 */
function getUserFriends($userid): mysqli_result
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

/**
 * Restituisce il numero di amici dell'utente
 * @param userid l'id dell'utente
 * @return int numero di amici dell'utente
 */
function getFriendsNumber($userid): int
{
    global $dbconn, $table_friends;

    $stmt = $dbconn->prepare("SELECT * FROM $table_friends WHERE userida = ? OR useridb = ?");
    $stmt->bind_param("ii", $userid, $userid);
    $stmt->execute();
    $esito = $stmt->get_result();

    if($esito === null || $esito === false) {
        return -1;
    }

    return $esito->num_rows;
}

/**
 * Restituisce la data in formato leggibile
 * @param unixtime il tempo in formato UNIX
 * @return string la data in formato leggibile
 */
function getFormattedDate($unixtime): string
{
    return date("d/m/Y", $unixtime);
}

/**
 * Restituisce la data e l'ora in formato leggibile
 * @param unixtime il tempo in formato UNIX
 * @return string la data e l'ora in formato leggibile
 */
function getFormattedDateTime($unixtime): string
{
    return date("d/m/Y H:i", $unixtime);
}

/**
 * Funzione interna.
 * @param category la categoria di cui ottenere la cartella
 * @return string la cartella relativa alla categoria, o "" se la categoria non è valida
 */
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

/**
 * Funzione interna.
 * @param category la categoria di cui ottenere le estensioni valide
 * @return array l'array di stringhe che rappresentano le estensioni accettate per gli elementi di quella categoria
 */
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

/**
 * Restituisce la stringa tagliata al carattere limit
 * @param string la stringa da tagliare
 * @param limit il punto in cui tagliare
 * @return mixed|string la stringa tagliata al carattere limit
 */
function getCutString($string, $limit): mixed
{
    return (strlen($string) > $limit ? substr($string,0,$limit) . "..." : $string);
}

/**
 * Restituisce il tempo stimato di lettura di una stringa.
 * @param string la stringa di cui calcolare il tempo stimato di lettura
 * @return int il tempo (in secondi) stimato di lettura di una stringa
 */
function getStringReadTime($string): int
{
    /*
     * In media una persona legge 200 parole al minuto (3,4 parole al secondo)
     */
    return floor(str_word_count(strip_tags($string)) / 3.4);
}

/**
 * Restituisce il tempo formattato in x ore e x minuti e x secondi
 * @param time il tempo in formato UNIX da formattare
 * @return string la stringa formattata che rappresenta il tempo
 */
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

/**
 * Restituisce il numero di mi piace e non mi piace di un contenuto. Su ArtU si può mettere mi piace e non mi piace a
 * contenuti degli utenti e alle loro pagine.
 * @param type il tipo del contenuto (content oppure page)
 * @param elementID l'id dell'elemento (id del contenuto o id della pagina)
 * @return array coppia $ret["likes"] e $ret["dislikes"] contenente rispettivamente il # di mi piace e di non mi piace
 */
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

/**
 * Restituisce il rating che ha dato un utente su un certo contenuto
 * @param userid l'id dell'utente di cui voglio sapere il rating
 * @param type il tipo del contenuto (content oppure page)
 * @param elementID l'id dell'elemento (id del contenuto o id della pagina)
 * @return int|mixed -1 se non ha votato, 1 se ha messo "mi piace", 0 se ha messo "non mi piace"
 */
function getUserRating($userid, $type, $elementID): mixed
{
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
 * Restituisce i commenti di un determinato contenuto
 * @param contentid l'id del contenuto di cui vedere i commenti
 * @return mysqli_result il result su cui fare il fetch_assoc()
 */
function getComments($contentid): mysqli_result
{
    global $dbconn, $table_users, $table_usercontent_comments;

    $stmt = $dbconn->prepare("SELECT $table_usercontent_comments.*, $table_users.username, $table_users.avatarURI FROM $table_usercontent_comments JOIN $table_users ON $table_usercontent_comments.userid = $table_users.id WHERE contentid = ? ORDER BY date DESC");
    $stmt->bind_param("i", $contentid);
    $stmt->execute();

    return $stmt->get_result();
}

/**
 * Determina se l'utente attualmente connesso può accedere al contenuto o meno.
 * @param contentid id del contenuto dell'utente a cui si vuole accedere
 * @return bool vero se si può interagire con il contenuto, falso altrimenti
 */
function canISeeContent($contentid): bool
{
    global $id;

    $dati = getContentById($contentid);
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

    $dati = getUserPageById($userpageid);
    if($dati != null) {
        if($dati["setting_visibility"] == 1 || (isLogged() && (amIFriendOf($dati["id"]) || $id == $dati["id"]))) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Determina se l'utente può commentare un contenuto.
 * @param contentid l'id del contenuto da commentare
 * @return int 0 se può commentare, -1 se non può vedere il contenuto, un numero diverso dai precedenti
 *              è il tempo passato dall'ultimo commento
 */
function canIComment($contentid): int
{
    global $id, $dbconn, $table_usercontent_comments, $time_between_comments;

    if(canISeeContent($contentid)) {
        $stmt = $dbconn->prepare("SELECT date FROM $table_usercontent_comments WHERE userid = ? ORDER BY date DESC LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $esito = $stmt->get_result();

        if($esito->num_rows == 0) {
            return 0;
        } else {
            $dati = $esito->fetch_assoc();
            $currentTime = time();

            if($currentTime - $dati["date"] >= $time_between_comments) {
                return 0;
            } else {
                return ($currentTime - $dati["date"]);
            }
        }
    } else {
        return -1;
    }
}

/**
 * Restituisce la stringa che identifica il file dell'avatar
 * @param uri l'uri preso dalle info del profilo
 * @return string l'uri stesso se uri non è vuoto, il nome del file dell'avatar di default altrimenti
 */
function getAvatarUri($uri): string
{
    global $defaultavatar_file;

    if(!isset($uri) || $uri == "") {
        return $defaultavatar_file;
    } else {
        return $uri;
    }
}

/**
 * Stampa dei contenuti
 * @param dbresult il risultato di una query al database, pronto per essere scorso
 * @param finalText se mostra un testo alla fine della sequenza
 */
function print_contents($dbresult, $finalText = null) {
    global $usercontent_directlyviewable, $defaultcontent_file, $explore_note_maxlength;

    if($dbresult == null) return;

    if($dbresult->num_rows == 0) {
        ?><p class="explore_item_textonly">Nessun elemento.</p><?php
        return;
    }

    while($row = $dbresult->fetch_assoc()) {
        $fileCategory = getContentFolderByCategory($row["type"]);
        $fileName = $row["id"] . "." . $row["contentExtension"];
        $filePath = $fileCategory . "/" . $fileName;

        $thumbnailPath = "";
        if($row["thumbnailExtension"] != "") {
            $thumbnailName = $row["id"] . "." . $row["thumbnailExtension"];
            $thumbnailPath = getContentFolderByCategory("thumbnail") . "/" . $thumbnailName;
        }

        if($thumbnailPath != "" || in_array($row["type"], $usercontent_directlyviewable)) {
            if($thumbnailPath != "") {
                $finalSource = $thumbnailPath;
            } else {
                $finalSource = $filePath;
            }
        } else {
            $finalSource = $fileCategory . "/" . $defaultcontent_file;
        }

        $tags = getTagArray($row["tags"]);

        $ratings = getRatings("content", $row["id"]);
    ?>
    <!-- possibile warning per mancanza di heading dell'article, in realtà c'è ma è più in basso -->
    <article class="explore_item bgcolor_secondary">
        <a href="view.php?id=<?php echo $row["id"]; ?>">
            <img class="explore_item_image" src="<?php echo $finalSource; ?>" alt="Immagine risorsa" />
            <div class="explore_item_content">
                <h4 class="explore_item_contenttitle"><?php echo $row["title"]; ?></h4>
                <pre class="explore_item_contenttags"><?php if(!empty($tags)) echo getPrintableArray($tags); ?></pre>
                <p><b><?php echo $ratings["likes"]; ?></b> 👍 | <b><?php echo $ratings["dislikes"]; ?> 👎</b></p>
                <p><?php echo getFixedString(getCutString($row["notes"], $explore_note_maxlength)); ?></p>
            </div>
        </a>
    </article>
    <?php }

    if($finalText != null) { ?>
        <article class="explore_item explore_item_textonly">
            <?php echo $finalText; ?>
        </article>
    <?php }
}

/**
 * Stampa della sezione "torna indietro"
 * @param redirect il link a cui tornare indietro
 * @param text il testo da mostrare sul pulsante
 */
function print_goBackSection($redirect = "./", $text = "🔙 Torna a Esplora") {
    ?>
    <div class="section_goback">
        <button onClick="redirect('<?php echo $redirect; ?>');" class="button bgcolor_secondary color_on_secondary"><?php echo $text; ?></button>
    </div>
    <?php
}

/**
 * Stampa della sezione dei pulsanti "mi piace" e "non mi piace"
 * @param myRating la mia valutazione (0, 1 o -1)
 * @param ratingsNumber i valori dei mi piace e non mi piace totali
 * @param type il tipo del contenuto (content oppure page)
 * @param elementID l'id dell'elemento (id del contenuto o id della pagina)
 */
function print_ratingSection($myRating, $ratingsNumber, $type, $elementID) {
    global $folder_backend;

    $classButtonLike = "";
    $classButtonDislike = "";
    if($myRating == 1) { // like
        $classButtonLike = "chosenrating";
    } else if($myRating == 0) { // dislike
        $classButtonDislike = "chosenrating";
    }

    ?>
    <button data-href="./<?php echo $folder_backend; ?>/chngrtng.php?value=like&type=<?php echo $type; ?>&elementid=<?php echo $elementID; ?>" id="like_button" class="button bgcolor_secondary_variant <?php echo $classButtonLike; ?> color_on_secondary changerating" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="like_counter"><?php echo $ratingsNumber["likes"]; ?></span>] 👍 Mi piace</button>&nbsp;
    <button data-href="./<?php echo $folder_backend; ?>/chngrtng.php?value=dislike&type=<?php echo $type; ?>&elementid=<?php echo $elementID; ?>" id="dislike_button" class="button bgcolor_secondary_variant <?php echo $classButtonDislike; ?> color_on_secondary changerating" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="dislike_counter"><?php echo $ratingsNumber["dislikes"]; ?></span>] 👎 Non mi piace</button>&nbsp;
    <?php
}

// -------- CODICE GLOBALE
// permette al codice del sito di accedere ai contenuti della sessione senza passare per $_SESSION (più comodo)
// inoltre è fornito il nome della pagina senza "php" e percorso
if (isLogged()) {
    $id = $_SESSION["id"];
    $username = $_SESSION["username"];
    $email = $_SESSION["email"];
    $creationDate = $_SESSION["creationDate"];
    $avataruri = $_SESSION["avatarUri"];

    $setting_visibility = $_SESSION["setting_visibility"];
    $setting_numElemsPerPage = $_SESSION["setting_numElemsPerPage"];
}


$url = str_replace(".php", "", explode('/', $_SERVER["PHP_SELF"]));
$pagename = end($url);
