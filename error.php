<?php $title = "⚠ Errore";
$description = "Si è verificato un errore e sei stato trasferito su questa pagina.";
$tags = "error";
require_once("config/config.php");
require_once($folder_include . "/functions.php");

if (isset($_GET["code"])) {
    switch ($_GET["code"]) {
        case 0: { // errore col database
            $emsg = "Colpa nostra! Non c'è altro da fare se non aspettare che il problema venga risolto...\n";
            break;
        }
    }
}

if(!isset($emsg)) {
    $emsg = "???";
}

// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");
?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item bgcolor_secondary color_on_secondary">
            <h3>Si è verificato un errore:</h3>
            <p><?php echo $emsg; ?></p>
        </div>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>