<?php
$title = "Esplora";
$description = "Qui puoi vedere foto, video, disegni e altre creazioni degli utenti.";
$tags = "photos, videos, drawings, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");
?>

<div class="main_content">
    <div class="flex_container">
        <div class="flex_item width_50 bgcolor_primary color_on_primary">
            <h1>Esplora</h1>
            <p><?php echo $loremipsum; ?></p>
        </div>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>