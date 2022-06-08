<?php $title = "🔡 Vedi contenuto";
$description = "Questo è il contenuto personalizzato di un utente.";
$tags = "customcontent";
require_once("config/config.php");
require_once($folder_include . "/dbconn.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/view.css">
<script src="<?php echo $folder_scripts; ?>/view.js"></script>

<link rel="stylesheet" href="<?php echo $folder_css; ?>/rating_system.css">
<script src="<?php echo $folder_scripts; ?>/rating_system.js"></script>
<?php require_once($folder_include . "/navbar.php");

$contentData = null;
if(isset($_GET["id"]) && $_GET["id"] != "") {
    $contentData = getUserContentById($_GET["id"]);
    if($contentData != null) {
        $permission = canISeeContent($_GET["id"]);
        if($permission) {
            $ratings = getRatings("content", $_GET["id"]);
            $classButtonLike = "bgcolor_secondary_variant";
            $classButtonDislike = "bgcolor_secondary_variant";

            if(isLogged()) {
                $myRating = getUserRating("content", $id, $_GET["id"]);

                if($myRating == 1) { // like
                    $classButtonLike = "chosenrating";
                } else if($myRating == 0) { // dislike
                    $classButtonDislike = "chosenrating";
                }
            }
        }
    }
}
?>

<main class="main_content">
    <div class="flex_container">
        <?php if($contentData == null) { ?>
            <div class="flex_item width_50 bgcolor_error color_on_error">
                <p>
                    Collegamento alla risorsa utente non valido.<br/>
                    Probabilmente siete arrivati qui da un link errato o avete inserito l'id a mano.<br/>
                    Lo schema corretto per visualizzare la risorsa di un utente è: <code>pagina.php?id=*idrisorsa*</code>
                </p>
                <br>
                <a href="./">🔙 Tornate alla homepage</a>
            </div>
        <?php } else if(!$permission) { ?>
            <div class="flex_item width_50 bgcolor_error color_on_error">
                <p>
                    Non puoi visualizzare questo contenuto perché <b><?php echo $contentData["username"]; ?></b> lo ha
                    impostato come 'Privato'.<br><br>
                    Per adesso, puoi accedere alla sua <a href="./page.php?username=<?php echo $contentData["username"]; ?>">pagina pubblica</a>
                    per inviargli una richiesta di amicizia.
                </p>
                <br>
                <a href="./">🔙 Tornate alla homepage</a>
            </div>
        <?php } else { ?>
            <div class="flex_item width_50 bgcolor_primary color_on_primary">
                <h2><?php echo $contentData["title"]; ?></h2>
                <?php if($contentData["type"] == "photo" || $contentData["type"] == "drawing") { ?>
                    <img class="view_content" src="./<?php echo $contentData["contentUri"]; ?>" alt="Contenuto dell'utente"/>
                <?php } ?>

                <?php if($contentData["type"] == "video") { ?>
                    <video class="view_content" controls="controls" autoplay muted>
                        <source type="video/<?php echo $contentData["contentExtension"]; ?>" src="./<?php echo $contentData["contentUri"]; ?>">
                        Il tuo browser non supporta il tag video...
                    </video>
                <?php } ?>

                <?php if($contentData["type"] == "music") { ?>
                    <audio class="view_content" controls="controls">
                        <source type="audio/<?php echo $contentData["contentExtension"]; ?>" src="./<?php echo $contentData["contentUri"]; ?>">
                        Il tuo browser non supporta il tag audio...
                    </audio>
                <?php } ?>

                <?php if($contentData["type"] == "text" || $contentData["type"] == "poetry") {
                        if($contentData["contentExtension"] == "txt") {
                            $filecontent = nl2br(file_get_contents("./" . $contentData["contentUri"]));
                            $cutcontent = getCutString($filecontent, $content_text_view_maxlength); ?>
                            <p class="textalign_start">
                            <span class="info_content">Tempo di lettura: <?php echo getFormattedTime(getStringReadTime($filecontent)); ?></span>
                            <?php echo $cutcontent; ?>

                            <?php if(strlen($filecontent) > strlen($cutcontent)) {
                                ?><span class="info_content">... per continuare a leggere, scarica il file dal pulsante sotto!</span><?php
                            }
                        } else {
                            ?><p class="textalign_center">Non riesco a mostrare questo file. Scaricarlo dal pulsante sotto!<?php
                        }
                    ?>
                    </p>
                <?php } ?>
                <br>
                <!-- Meccanismo like e dislike -->
                <a class="changerating" id="changelike" href="./<?php echo $folder_backend; ?>/chngrtng.php?value=like&type=content&elementid=<?php echo $contentData["id"]; ?>"><button id="like_button" class="button bgcolor_secondary_variant <?php echo $classButtonLike; ?> color_on_secondary" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="like_counter"><?php echo $ratings["likes"]; ?></span>] 👍 Mi piace</button></a>&nbsp;
                <a class="changerating" id="changedislike" href="./<?php echo $folder_backend; ?>/chngrtng.php?value=dislike&type=content&elementid=<?php echo $contentData["id"]; ?>"><button id="dislike_button" class="button bgcolor_secondary_variant <?php echo $classButtonDislike; ?> color_on_secondary" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="dislike_counter"><?php echo $ratings["dislikes"]; ?></span>] 👎 Non mi piace</button></a>&nbsp;
                <!-- Download contenuto -->
                <a class="downloadcontent" href="./<?php echo $folder_backend; ?>/download.php?id=<?php echo $contentData["id"]; ?>"><button class="button bgcolor_secondary color_on_secondary">📥 Scarica contenuto originale</button></a>
                <!-- Note -->
                <?php if(!empty($contentData["notes"])) { ?>
                <div class="textalign_start">
                    <h3>Descrizione</h3>
                    <div class="description">
                        <p><?php echo nl2br($contentData["notes"]); ?></p>
                    </div>
                </div>
                <?php } ?>
                <hr>
                <div class="textalign_start">
                    <h2>Informazioni sul contenuto</h2>
                    <p>
                        <a target="_blank" title="Clicca per aprire la pagina dell'utente" href="page.php?username=<?php echo $contentData["username"]; ?>"><img class="avatar avatar_medium" src="./<?php echo $folder_avatars . "/" . $contentData["avatarUri"]; ?>" alt=""/><br>
                        Creatore: <b><?php echo $contentData["username"]; ?></b></a> <?php if(isLogged() && $id == $contentData["id"]) { ?><i id="itsyou"><small>Ehi guardate, siete voi!</small></i><?php } ?><br>
                        Pubblicazione: <b><?php echo getFormattedDateTime($contentData["creationDate"]); ?></b><br>
                        Tags: <code><?php if(!empty($contentData["tags"])) echo getPrintableArray($contentData["tags"]); else echo "nessuno"; ?></code><br>
                        Privato?: <?php if($contentData["private"] == "1") {echo "sì"; echo " (riesci a vedere questo contenuto perché "; if($id == $contentData["id"]) { echo "l'hai creato te"; } else { echo "sei amico del creatore"; } echo ")"; } else { echo "no"; } ?>
                    </p>
                </div>
                <br>
                <a href="index.php">🔙 Torna a Esplora</a>
            </  div>
        <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
