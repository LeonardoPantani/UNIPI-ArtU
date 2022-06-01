<?php $title = "Homepage";
$description = "Pagina iniziale.";
$tags = "";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");
?>

<div class="main_content">
    <h1>Vi diamo il benvenuto su <?php echo $service_name; ?></h1>
    <p><?php echo $loremipsum; ?></p>
</div>

<?php require_once($folder_include . "/footer.php"); ?>