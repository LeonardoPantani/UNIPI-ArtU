<?php
$title = "🔍 Contenuti dell'utente";
$description = "Qui puoi vedere foto, video, disegni e altre creazioni specificando dei parametri.";
$tags = "photo, video, drawing, music, search";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");

$searcheduserid = null;
if(isset($_GET["username"]) && $_GET["username"] != "") {
    $searcheduserid = getUserIdByUsername($_GET["username"]);
    if($searcheduserid != null) { // se l'utente esiste, ok
        $amIFriend = amIFriendOf($searcheduserid);

        if($amIFriend || $id == $searcheduserid) {
            if(isset($_GET["private"]) && ($_GET["private"] == 0 || $_GET["private"] == 1)) {
                $mode = "AND $table_usercontent.private = " . $_GET["private"];

                if($_GET["private"] == 0) {
                    $searchCriteriaText = "pubblici";
                } else {
                    $searchCriteriaText = "privati";
                }
                $searchCriteria = "stai visualizzando i contenuti marcati come <b>" . $searchCriteriaText . "</b>.";
            } else {
                $mode = "";
                $searchCriteria = "stai visualizzando i contenuti sia <b>pubblici</b> che <b>privati</b>.";
            }
        } else {
            $mode = "AND $table_usercontent.private = 0";
            $searchCriteria = "stai visualizzando i contenuti <b>pubblici</b> perché non sei amico di di questo utente.";
        }

        // ottengo tutti i contenuti degli utenti pubblici
        $stmt = $dbconn->prepare("SELECT $table_users.username, $table_usercontent.* FROM $table_users JOIN $table_usercontent ON $table_users.id = $table_usercontent.userid WHERE $table_users.id = ? $mode ORDER BY $table_usercontent.creationDate DESC");
        $stmt->bind_param("i", $searcheduserid);
        $stmt->execute();
        $esito = $stmt->get_result();
    }
}
?>

<main class="main_content">
    <?php if($searcheduserid == null) { ?>
        <div class="flex_item bgcolor_error color_on_error">
            <p>
                Nome utente non valido.<br/>
                Probabilmente siete arrivati qui da un link errato o avete inserito il nome utente a mano.<br/>
                Lo schema corretto per visualizzare i contenuti di un utente è: <code>pagina.php?username=*nomeutente*</code>
            </p>
            <?php print_goBackSection(); ?>
        </div>
    <?php } else { ?>
        <div class="flex_container">
            <div class="flex_item bgcolor_primary color_on_primary">
                <h1><?php echo $title; ?></h1>
                <p>Vedi solo i contenuti che dell'utente che ti interessa. Questa pagina sarà aggiornata con nuove funzionalità in futuro.</p>
                <div class="section_description">
                    <p>Criteri di ricerca attivi:</p>
                    <p><?php echo $searchCriteria; ?></p>
                </div>
                <div class="arrow down arrow_small"></div>
            </div>
        </div>

        <div class="explore_container textalign_center">
            <?php print_contents($esito); ?>
        </div>
    <?php } ?>
</main>

<?php require_once($folder_include . "/footer.php"); ?>