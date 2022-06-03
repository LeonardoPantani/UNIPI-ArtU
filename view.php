<?php $title = "Vedi Contenuto";
$description = "Questo è il contenuto personalizzato di un utente.";
$tags = "customcontent";
require_once("config/config.php");
require_once($folder_include . "/dbconn.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");

if(!isset($_GET["id"]) || $_GET["id"] == "") {
    $usercontentdata = null;
}  else {
    $usercontentdata = getUserContentById($_GET["id"]);
}

// TODO continuare la pagina di visualizzazione
?>

<div class="main_content">
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
                <h1>TODO</h1>
                <p>
                    Tipo risorsa: <b><?php echo $usercontentdata["type"]; ?></b><br>
                    Utente creatore: <b><?php echo $usercontentdata["username"]; ?></b><br>
                    Data creazione: <b><?php echo getFormattedDateTime($usercontentdata["creationDate"]); ?></b><br>
                </p>
                <br>
                <a href="./">🔙 Torna alla homepage</a>
            </div>
        <?php } ?>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>
