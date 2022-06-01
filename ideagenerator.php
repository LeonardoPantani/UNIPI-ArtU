<?php
$title = "Generatore di idee";
$description = "A corto di idee? Questo generatore casuale di parole potrebbe darti qualche spunto per la creazione perfetta!";
$tags = "random generator, idea generator, photos, videos, drawings, music";
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
            <h1>🔮 Generatore di idee</h1>
            <p><?php echo $service_motto; ?>!</p>
        </div>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>