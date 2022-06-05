<?php $title = "Vedi Contenuto";
$description = "Questo è il contenuto personalizzato di un utente.";
$tags = "customcontent";
require_once("config/config.php");
require_once($folder_include . "/dbconn.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/view.css">
<?php require_once($folder_include . "/navbar.php");

if(!isset($_GET["id"]) || $_GET["id"] == "") {
    $usercontentdata = null;
}  else {
    $usercontentdata = getUserContentById($_GET["id"]);
}
?>

<main class="main_content">
    <div class="flex_container">
        <?php if($usercontentdata == null) { ?>
            <div class="flex_item width_50 bgcolor_error color_on_error">
                <p>
                    Collegamento alla risorsa utente non valido.<br/>
                    Probabilmente siete arrivati qui da un link errato o avete inserito l'id a mano.<br/>
                    Lo schema corretto per visualizzare la risorsa di un utente è: <code>pagina.php?id=*idrisorsa*</code>
                </p>
                <br>
                <a href="./">🔙 Torna alla homepage</a>
            </div>
        <?php } else { ?>
            <div class="flex_item width_50 bgcolor_primary color_on_primary">
                <h2><?php echo $usercontentdata["title"]; ?></h2>
                <div class="view_maindiv">
                    <?php if($usercontentdata["type"] == "photo" || $usercontentdata["type"] == "drawing") { ?>
                        <img class="view_content" src="./<?php echo $usercontentdata["contentUri"]; ?>"  alt="Contenuto dell'utente"/>
                    <?php } ?>

                    <?php if($usercontentdata["type"] == "video") { ?>
                        <video class="view_content" controls="controls" autoplay muted>
                            <source type="video/<?php echo $usercontentdata["contentExtension"]; ?>" src="./<?php echo $usercontentdata["contentUri"]; ?>">
                            Il tuo browser non supporta il tag video...
                        </video>
                    <?php } ?>

                    <?php if($usercontentdata["type"] == "music") { ?>
                        <audio class="view_content" controls="controls">
                            <source type="audio/<?php echo $usercontentdata["contentExtension"]; ?>" src="./<?php echo $usercontentdata["contentUri"]; ?>">
                            Il tuo browser non supporta il tag audio...
                        </audio>
                    <?php } ?>

                    <?php if($usercontentdata["type"] == "text" || $usercontentdata["type"] == "poetry") {
                            if($usercontentdata["contentExtension"] == "txt") {
                                $filecontent = nl2br(file_get_contents("./" . $usercontentdata["contentUri"]));
                                $cutcontent = getCutString($filecontent, $content_text_view_maxlength); ?>
                                <p class="view_content textalign_start">
                                <span class="info_content">Tempo di lettura: <?php echo gmdate("i:s", getStringReadTime($filecontent)); ?></span>
                                <br><br>
                                <?php
                                echo $cutcontent;

                                if(strlen($filecontent) != strlen($cutcontent)) {
                                    ?><br><br><span class="info_content">... per continuare a leggere, scarica il file dal pulsante sotto!</span><?php
                                }
                            } else {
                                ?><p class="textalign_center">Non riesco a mostrare questo file. Scaricarlo dal pulsante sotto!<?php
                            }
                        ?>
                        </p>
                    <?php } ?>
                    <br>
                    <a href="./<?php echo $folder_backend; ?>/download.php?id=<?php echo $usercontentdata["id"]; ?>"><button class="button bgcolor_secondary color_on_secondary">📥 Scarica contenuto originale</button></a>
                    <p class="textalign_start"><?php echo $usercontentdata["notes"]; ?></p>
                </div>
                <hr>
                <div class="textalign_start">
                    <h2>Informazioni sul contenuto</h2>
                    <p>
                        Tipo risorsa: <b><?php echo $usercontentdata["type"]; ?></b><br>
                        Utente creatore: <b><a title="Clicca per accedere alla pagina dell'utente" href="page.php?username=<?php echo $usercontentdata["username"]; ?>"><?php echo $usercontentdata["username"]; ?></a></b> <?php if($username == $usercontentdata["username"]) { ?><i><small>Ehi guardate, siete voi!</small></i><?php } ?><br>
                        Data creazione: <b><?php echo getFormattedDateTime($usercontentdata["creationDate"]); ?></b><br>
                    </p>
                </div>
                <br>
                <a href="index.php">🔙 Torna a Esplora</a>
            </  div>
        <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
