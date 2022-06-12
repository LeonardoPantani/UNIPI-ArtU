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
$myRating = null;
if(isset($_GET["id"]) && $_GET["id"] != "") {
    $contentData = getContentById($_GET["id"]);
    if($contentData != null) {
        $permission = canISeeContent($_GET["id"]);
        if($permission) {
            $ratings = getRatings("content", $_GET["id"]);
            $comments = getComments($_GET["id"]);

            if(isLogged()) {
                $myRating = getUserRating($id, "content", $_GET["id"]);
                $canICommentResult = canIComment($_GET["id"]);
            }
        }
    }
}
?>

<main class="main_content">
    <div class="flex_container">
        <?php if($contentData == null) { ?>
            <div class="flex_item bgcolor_error color_on_error">
                <p>
                    Collegamento alla risorsa utente non valido.<br/>
                    Probabilmente siete arrivati qui da un link errato o avete inserito l'id a mano.<br/>
                    Lo schema corretto per visualizzare la risorsa di un utente è: <code>pagina.php?id=*idrisorsa*</code>
                </p>
                <?php print_goBackSection(); ?>
            </div>
        <?php } else if(!$permission) { ?>
            <div class="flex_item bgcolor_error color_on_error">
                <p>
                    Non puoi visualizzare questo contenuto perché <b><?php echo $contentData["username"]; ?></b> lo ha
                    impostato come 'Privato'.<br><br>
                    Per adesso, puoi accedere alla sua <a href="./page.php?username=<?php echo $contentData["username"]; ?>">pagina pubblica</a>
                    per inviargli una richiesta di amicizia.
                </p>
                <?php print_goBackSection(); ?>
            </div>
        <?php } else { ?>
            <div class="flex_item bgcolor_primary color_on_primary">
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
                <?php print_ratingSection($myRating, $ratings, "content", $contentData["id"]); ?>
                <!-- Download contenuto -->
                <button class="button bgcolor_secondary color_on_secondary" onClick="redirect('./<?php echo $folder_backend; ?>/download.php?id=<?php echo $contentData["id"]; ?>');">📥 Scarica contenuto originale</button>
                <!-- Note -->
                <?php if(!empty($contentData["notes"])) { ?>
                <div class="textalign_start">
                    <h3>Descrizione</h3>
                    <div class="section_description">
                        <p><?php echo nl2br($contentData["notes"]); ?></p>
                    </div>
                </div>
                <?php } ?>
                <hr>
                <div class="textalign_start">
                    <h2>Informazioni sul contenuto</h2>
                    <p>
                        <a target="_blank" title="Clicca per aprire la pagina dell'utente" href="page.php?username=<?php echo $contentData["username"]; ?>"><img class="avatar avatar_medium" src="./<?php echo $folder_avatars . "/" . getAvatarUri($contentData["avatarUri"]); ?>" alt="Immagine utente"/><br>
                        Creatore: <b><?php echo $contentData["username"]; ?></b></a> <?php if(isLogged() && $id == $contentData["id"]) { ?><i id="itsyou"><small>Ehi guarda, sei tu!</small></i><?php } ?><br>
                        Pubblicazione: <b><?php echo getFormattedDateTime($contentData["creationDate"]); ?></b><br>
                        Tags: <code><?php if(!empty($contentData["tags"])) echo getPrintableArray($contentData["tags"]); else echo "nessuno"; ?></code><br>
                        Privato?: <?php if($contentData["private"] == "1") {echo "sì"; echo " (riesci a vedere questo contenuto perché "; if($id == $contentData["userid"]) { echo "l'hai creato te"; } else { echo "sei amico del creatore"; } echo ")"; } else { echo "no"; } ?>
                    </p>
                </div>
                <hr>
                <div class="textalign_start">
                    <p id="commentmaxlength" class="gone"><?php echo $comment_maxlength; ?></p>
                    <h2>Commenti (<span id="comment_number"><?php echo $comments->num_rows; ?></span>)</h2>
                    <?php if(!isLogged()) { ?><p><a href="./auth.php">Effettua l'accesso</a> per poter commentare.</p><?php } ?>
                    <form id="comment_form" action="./<?php echo $folder_backend; ?>/addcmnt.php" method="POST">
                        <input id="contentid" name="contentid" class="gone" value="<?php echo $_GET["id"]; ?>" />
                        <textarea id="text" class="text" name="text" placeholder="Inserisci un commento. Massimo <?php echo $comment_maxlength; ?> caratteri." maxlength="<?php echo $comment_maxlength; ?>" <?php if(!isLogged()) { echo "disabled"; } ?>></textarea><br>
                        <input type="submit" name="button" value="📨 Invia commento" <?php if(!isLogged()) { echo "disabled"; } ?> />&nbsp;<span id="addcmnt_result" class="gone"></span>
                    </form>
                    <!-- possibile warning per mancanza di heading della section, in realtà c'è ma è più in basso -->
                    <section class="comment_section">
                        <?php while($row = $comments->fetch_assoc()) { ?>
                            <div class="comment comment<?php echo $row["id"]; ?>">
                                <h4><a href="./page.php?username=<?php echo $row["username"]; ?>"><?php echo $row["username"]; ?></a></h4>
                                <pre><?php echo getFormattedDateTime($row["date"]); ?> <?php if($row["userid"] == $id) { ?><span>| <a class="deletecomment" href="./<?php echo $folder_backend; ?>/delcmnt.php?id=<?php echo $row["id"]; ?>" data-id="<?php echo $row["id"]; ?>">❌ Elimina commento</a></span><?php } ?></pre>
                                <p><?php echo $row["text"]; ?></p>
                            </div>
                        <?php } ?>
                        <p id="nocomments" class="<?php if($comments->num_rows != 0) { echo "gone"; } ?>">Nessun commento, per adesso.</p>
                    </section>
                </div>
                <?php print_goBackSection(); ?>
            </div>
        <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
