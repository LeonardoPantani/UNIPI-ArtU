<?php
$title = "Modifica pagina";
$description = "Qui puoi modificare la tua pagina pubblica.";
$tags = "";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
kickGuestUser();
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/editpage.css">
<script src="<?php echo $folder_scripts; ?>/editpage.js"></script>
<?php
require_once($folder_include . "/navbar.php");

$stmt = $dbconn->prepare("SELECT * FROM $table_pages WHERE userid = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$esito = $stmt->get_result();
$nrighe = $esito->num_rows;

if($nrighe == 0) {
    $content = "";
} else {
    $dati = $esito->fetch_assoc();
    $content = $dati["content"];
}
?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item width_50 bgcolor_primary color_on_primary">
            <h1>Modifica pagina pubblica</h1>
            <a href="./page.php?username=<?php echo $username; ?>">📃 Vedi Pagina Pubblica</a>
            <p class="color_important">Nota: per il momento non è possibile usare tag HTML, ci scusiamo per il disagio.</p>
            <form id="editpage_form" autocomplete="off" action="./<?php echo $folder_backend; ?>/editpg.php" method="POST">
                <textarea id="htmeditor" name="htmeditor" class="width_100" rows="20" placeholder="Scrivi qualcosa... Massimo <?php echo $content_page_maxlength; ?> caratteri" maxlength="<?php echo $content_page_maxlength; ?>"><?php echo htmlspecialchars($content); ?></textarea>

                <br><br>
                <input id="goback" type="button" value="🔙 Indietro" class="button bgcolor_secondary color_on_secondary" />
                <input id="editpage_submitform" type="submit" value="🔃 Aggiorna Pagina" class="button bgcolor_secondary color_on_secondary" />
                <p id="result"></p>
            </form>
        </div>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>