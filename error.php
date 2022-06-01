<?php $title = "Errore";
$description = "Si è verificato un errore e sei stato trasferito su questa pagina.";
$tags = "";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");
?>

<?php
if (isset($_GET["code"])) {
    switch ($_GET["code"]) {
        case 0: {
                $emsg = "Impossibile stabilire un collegamento col database.";
                break;
            }
        default: {
                header("Location:./index.php");
                return;
            }
    }
} else {
    header("Location:./index.php");
    return;
}
?>

<div class="main_content">
    <div class="flex_container">
        <div class="flex_container_item bgcolor_secondary color_on_secondary">
            <h3>Si è verificato un errore:</h3>
            <p><?php echo $emsg; ?></p>
        </div>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>