<?php
$title = "🌍 Esplora";
$description = "Qui puoi vedere foto, video, disegni e altre creazioni degli utenti.";
$tags = "photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/index.css">
<script src="<?php echo $folder_scripts; ?>/index.js"></script>
<?php
require_once($folder_include . "/navbar.php");
// paginazione
$paginationNumber = 5; // TODO rendere configurabile
$stmt = $dbconn->prepare("SELECT max(id) as maxid FROM $table_usercontent ORDER BY id DESC LIMIT 1");
$stmt->execute();
$esito = $stmt->get_result();
$maxContentId = $esito->fetch_assoc()["maxid"];

if(!isset($_GET["id"])) {
    $maxid = $maxContentId;
} else {
    $maxid = $_GET["id"];
}
$pagPrev = $maxid + $paginationNumber;
$pagNext = $maxid - $paginationNumber;

// ottengo tutti i contenuti degli utenti pubblici
$stmt = $dbconn->prepare("SELECT $table_usercontent.*, $table_users.username FROM $table_usercontent JOIN $table_users ON $table_usercontent.userid = $table_users.id WHERE $table_usercontent.id <= ? AND $table_usercontent.private = 0 ORDER BY id DESC LIMIT ?");
$stmt->bind_param("ii", $maxid, $paginationNumber);
$stmt->execute();
$esito = $stmt->get_result();
?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item bgcolor_primary color_on_primary">
            <h1><?php echo $title; ?></h1>
            <p>Qui sono mostrati i contenuti della community. I contenuti marcati come privati non sono visibili.</p>
            <div class="arrow down arrow_small"></div>
        </div>
    </div>

    <div class="explore_container textalign_center">
        <?php print_contents($esito); ?>
    </div>

    <div class="textalign_center">
        <?php if($pagPrev <= $maxContentId) { ?>
            <button onClick="redirect('./?id=<?php echo $pagPrev; ?>');" class="button bgcolor_primary color_on_primary">⏪ Precedenti</button>
        <?php }
        if($pagNext > 0) { ?>
            <button onClick="redirect('./?id=<?php echo $pagNext; ?>');" class="button bgcolor_primary color_on_primary">⏩ Successivi</button>
        <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>