<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

// controllo che i dati indispensabili siano presenti
if(!isset($_POST["content_category"]) || !isset($_POST["content_tags"]) || !isset($_POST["content_notes"]) || !isset($_FILES["content_file"]) || !isset($_FILES["content_thumbnail"])) {
    echo _("Dati invalidi");
    return;
}

// valido la categoria
$category = $_POST["content_category"];
if(!in_array($category, $usercontent_types)) {
    echo _("Categoria contenuto non valida.");
    return;
}

// valido il titolo
$content_title = $_POST["content_title"];
if(strlen($content_title) > $content_title_maxlength) {
    echo _("Titolo troppo lungo. Lunghezza massima: " . $content_title_maxlength);
    return;
}

if(!preg_match('/'. $content_title_regex .'/', $content_title)) {
    echo _("Formato titolo non valido");
    return;
}

// valido il file principale
if($_FILES["content_file"]["error"] != 0) {
    echo _("Errore upload file principale.");
    return;
}

if($_FILES["content_file"]["size"] > $content_file_maxsize) {
    echo _("File troppo grande.");
    return;
}

// valido l'estensione del file principale
$content_file_extension = pathinfo($_FILES["content_file"]["name"])["extension"];
$valid_extensions = getValidExtensionsByCategory($category);
$folder_to_save = getContentFolderByCategory($category);

if(!in_array($content_file_extension, $valid_extensions)) {
    echo _("Estensione file non valida per il tipo specificato.");
    return;
}

// valido l'estensione della thumbnail (se c'è)
$content_thumbnail_extension = "";
if($_FILES["content_thumbnail"]["error"] == 0) {
    $content_thumbnail_extension = pathinfo($_FILES["content_thumbnail"]["name"])["extension"];
    if(!in_array($content_thumbnail_extension, $accept_thumbnail)) {
        echo _("Estensione miniatura non valida.");
        return;
    }

    if($_FILES["content_thumbnail"]["size"] > $content_thumbnail_maxsize) {
        echo _("File miniatura troppo grande.");
        return;
    }
}

// valido i tags (se ci sono)
$content_tags = "";
if($_POST["content_tags"] != "") {
    if(!preg_match('/^[a-zA-Z_]+(?=(,?\s*))(?:\1[a-zA-Z_]+)+$/', $_POST["content_tags"])) {
        echo _("Formato tag non valido.");
        return;
    }

    $trimmedtags = trim(str_replace(" ", "", $_POST["content_tags"]));
    $tagslist = explode(",", $trimmedtags);

    if(sizeof($tagslist) > $content_tag_maxnumber) {
        echo _("Troppi tag! Al massimo ne puoi specificare $content_tag_maxnumber.");
        return;
    }

    foreach($tagslist as $tag) {
        if(strlen($tag) > $content_tag_maxlength) {
            echo _("I singoli tag non devono superare i $content_tag_maxlength caratteri di lunghezza");
            return;
        }
    }

    $content_tags = serialize($tagslist);
}

// controllo che le note non superino il limite di caratteri
$content_notes = "";
if($_POST["content_notes"] != "") {
    if(strlen($_POST["content_notes"]) > $content_note_maxlength) {
        echo _("Campo note troppo lungo.");
        return;
    }
    $content_notes = trim(htmlspecialchars($_POST["content_notes"]));
}

// controllo settings
// controllo che l'impostazione "private" sia valida
$content_setting_private = 0;
if(isset($_POST["content_setting_private"])) {
    if($_POST["content_setting_private"] != "1") {
        echo _("Valore del campo impostazioni 'privato' non valido.");
        return;
    }
    $content_setting_private = intval($_POST["content_setting_private"]);
}

// TUTTO OK DOPO QUI
$currentTime = time();

$dbconn->begin_transaction(); // inizio transazione

$stmt = $dbconn->prepare("INSERT INTO $table_usercontent (type, creationDate, title, tags, notes, private, userid, contentExtension, thumbnailExtension) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssiiss", $category, $currentTime, $content_title, $content_tags, $content_notes, $content_setting_private, $id, $content_file_extension, $content_thumbnail_extension);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo _("Errore durante l'upload del file.");
    $dbconn->rollback(); // rollback
    return;
}

// carico il file
if (!move_uploaded_file($_FILES["content_file"]["tmp_name"], "../" . $folder_to_save . "/" . $stmt->insert_id . "." . $content_file_extension)) {
    echo _("Errore interno.");
    $dbconn->rollback(); // rollback
    return;
}

// carico la miniatura (se presente)
if($_FILES["content_thumbnail"]["error"] == 0) {
    if (!move_uploaded_file($_FILES["content_thumbnail"]["tmp_name"], "../" . $folder_thumbnail . "/" . $stmt->insert_id . "." . $content_thumbnail_extension)) {
        echo _("Errore interno.");
        $dbconn->rollback(); // rollback
        return;
    }
}

$dbconn->commit(); // commit
echo "uploadcontent_ok:".$stmt->insert_id;