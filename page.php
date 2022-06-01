<?php $title = "Pagina utente";
$description = "Questa è la pagina personalizzata di un utente.";
$tags = "custompage";
require_once("config/config.php");
require_once($folder_include . "/dbconn.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");

if(!isset($_GET["username"]) || $_GET["username"] == "") {
    $pageuserdata = null;
}  else {
    $pageuserdata = getUserDataByUsername($_GET["username"]);
}
?>

<div class="main_content">
    <div class="flex_container">
        <?php if($pageuserdata == null) { ?>
            <div class="flex_item width_50 bgcolor_error color_on_error">
                <p>
                    Collegamento alla pagina dell'utente non valido.<br/>
                    Probabilmente siete arrivati qui da un link errato o avete inserito il nome utente a mano.<br/>
                    Lo schema corretto per visualizzare la pagina di un utente è: <code>pagina.php?username=*nomeutente*</code>
                </p>
                <br>
                <a href="./">🔙 Torna alla homepage</a>
            </div>
        <?php } else if($username != $pageuserdata["username"] && $pageuserdata["visibility"] == 0) { ?>
            <div class="flex_item width_50 bgcolor_warning color_on_warning">
                <p>
                    <b><?php echo $pageuserdata["username"]; ?></b> non consente la visualizzazione della propria pagina.
                </p>
                <br>
                <a href="./">🔙 Torna alla homepage</a>
            </div>
        <?php } else { ?>
            <div class="flex_item width_50 bgcolor_primary color_on_primary">
                <div class="flex_container">
                    <div class="flex_item width_50 bgcolor_primary color_on_primary">
                        <img class="avatar avatar_big" src="<?php echo "./" . $folder_avatars . "/" . $pageuserdata["avatarUri"]; ?>"  alt="Immagine profilo utente"/>
                    </div>
                    <div class="flex_item width_50 bgcolor_primary color_on_primary textalign_start">
                        <h1><?php echo $pageuserdata["username"]; ?></h1>

                        <?php if($username == $pageuserdata["username"] && $visibility) { ?>
                            <p class="color_important">Nota: avete impostato la visibilità del profilo su <b>privata</b>, pertanto nessuno eccetto voi può vedere questa pagina.</p>
                        <?php } else { ?>
                            <p>Così è come vedranno gli altri utenti la pagina del vostro profilo.</p>
                        <?php } ?>
                    </div>
                </div>
                <hr>
                <h1>Altro testo...</h1>
            </div>
        <?php } ?>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>
