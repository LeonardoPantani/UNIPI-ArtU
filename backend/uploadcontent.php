<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

kickGuestUser(true);

// controllo che i dati indispensabili siano presenti
if(!isset($_POST["content_category"]) || !isset($_POST["content_tags"]) || !isset($_POST["content_notes"]) || !isset($_FILES["content_file"]) || !isset($_FILES["content_thumbnail"])) {
    echo "error_invalid";
    return;
}

// valido la categoria
$category = $_POST["content_category"];
if(!in_array($category, $usercontent_types)) {
    echo "invalid_content_type";
    return;
}

// valido il file principale
if($_FILES["content_file"]["error"] != 0) {
    echo "invalid_content_file";
    return;
}

if($_FILES["content_file"]["size"] > $content_file_maxsize) {
    echo "content_file_too_big";
    return;
}

// valido l'estensione del file principale
$content_file_extension = pathinfo($_FILES["content_file"]["name"])["extension"];
$valid_extensions = array();
$folder_to_save = $folder_usercontent; // default

switch($category) {
    case "photo": {
        $valid_extensions = $accept_photo;
        $folder_to_save = $folder_photo;
        break;
    }
    case "video": {
        $valid_extensions = $accept_video;
        $folder_to_save = $folder_video;
        break;
    }
    case "drawing": {
        $valid_extensions = $accept_drawing;
        $folder_to_save = $folder_drawing;
        break;
    }
    case "music": {
        $valid_extensions = $accept_music;
        $folder_to_save = $folder_music;
        break;
    }
    case "text": {
        $valid_extensions = $accept_text;
        $folder_to_save = $folder_text;
        break;
    }
    case "poetry": {
        $valid_extensions = $accept_poetry;
        $folder_to_save = $folder_poetry;
        break;
    }
    default: {
        break;
    }
}
if(!in_array($content_file_extension, $valid_extensions)) {
    echo "invalid_content_file_extensions";
    return;
}

// valido l'estensione della thumbnail (se c'è)
if($_FILES["content_thumbnail"]["error"] == 0) {
    $content_thumbnail_extension = pathinfo($_FILES["content_thumbnail"]["name"])["extension"];
    if(!in_array($content_thumbnail_extension, $accept_thumbnail)) {
        echo "invalid_content_thumbnail_extensions";
        return;
    }

    if($_FILES["content_thumbnail"]["size"] > $content_thumbnail_maxsize) {
        echo "content_thumbnail_too_big";
        return;
    }
}

// valido i tags (se ci sono)
$content_tags = "";
if($_POST["content_tags"] != "") {
    if(!preg_match('/^[a-zA-Z_]+(?=(,?\s*))(?:\1[a-zA-Z_]+)+$/', $_POST["content_tags"])) {
        echo "invalid_tags";
        return;
    }

    $trimmedtags = trim($_POST["content_tags"]);
    $tagslist = explode(",", $trimmedtags);

    if(sizeof($tagslist) > $content_tag_maxnumber) {
        echo "tag_toomany";
        return;
    }

    foreach($tagslist as &$tag) {
        if(strlen($tag) > $content_tag_maxlength) {
            echo "tag_toolong";
            return;
        }
    }

    $content_tags = $_POST["content_tags"];
}

// controllo che le note non superino il limite di caratteri
$content_notes = "";
if($_POST["content_notes"] != "") {
    if(strlen($_POST["content_notes"]) > $content_note_maxlength) {
        echo "note_toolong";
        return;
    }
    $content_notes = $_POST["content_notes"];
}

// controllo settings
// controllo che l'impostazione "private" sia valida
$content_setting_private = 0;
if(isset($_POST["content_setting_private"])) {
    if($_POST["content_setting_private"] != "1") {
        echo "setting_private_invalid";
        return;
    }
    $content_setting_private = intval($_POST["content_setting_private"]);
}

// TUTTO OK DOPO QUI
$currentTime = time();

$dbconn->begin_transaction(); // inizio transazione

$stmt = $dbconn->prepare("INSERT INTO $table_usercontent (type, creationDate, tags, notes, private, userid) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissii", $category, $currentTime, $content_tags, $content_notes, $content_setting_private, $id);
$stmt->execute();

if ($stmt->affected_rows != 1) {
    echo "error_uploadcontent";
    $dbconn->rollback(); // rollback
    return;
}

// carico il file
if (!move_uploaded_file($_FILES["content_file"]["tmp_name"], "../" . $folder_to_save . "/" . $stmt->insert_id . "." . $content_file_extension)) {
    echo "error_movecontent";
    $dbconn->rollback(); // rollback
    return;
}

$dbconn->commit(); // commit
echo "uploadcontent_ok:".$stmt->insert_id;