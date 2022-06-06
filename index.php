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
// ottengo tutti i contenuti degli utenti pubblici
$stmt = $dbconn->prepare("SELECT $table_usercontent.*, $table_users.username FROM $table_usercontent JOIN $table_users ON $table_usercontent.userid = $table_users.id WHERE private = 0 ORDER BY creationDate DESC");
$stmt->execute();
$esito = $stmt->get_result();
?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item width_50 bgcolor_primary color_on_primary">
            <h1><?php echo $title; ?></h1>
            <cite><?php echo $service_motto; ?></cite>
            <br><i class="arrow down arrow_small"></i>
        </div>
    </div>

    <section class="explore_container">
        <?php
        while($row = $esito->fetch_assoc()) {
            $fileCategory = getContentFolderByCategory($row["type"]);
            $fileName = $row["id"] . "." . $row["contentExtension"];
            $filePath = $fileCategory . "/" . $fileName;

            $thumbnailPath = "";
            if($row["thumbnailExtension"] != "") {
                $thumbnailName = $row["id"] . "." . $row["thumbnailExtension"];
                $thumbnailPath = getContentFolderByCategory("thumbnail") . "/" . $thumbnailName;
            }

            if($thumbnailPath != "" || in_array($row["type"], $usercontent_directlyviewable)) {
                if($thumbnailPath != "") {
                    $finalSource = $thumbnailPath;
                } else {
                    $finalSource = $filePath;
                }
            } else {
                $finalSource = $fileCategory . "/" . $defaultcontent_file;
            }

            $tags = getTagArray($row["tags"]);
        ?>
        <article class="explore_item bgcolor_secondary">
            <a href="view.php?id=<?php echo $row["id"]; ?>">
                <picture class="explore_item_imagecontainer">
                    <img class="explore_item_image" src="<?php echo $finalSource; ?>" alt="Immagine risorsa" />
                </picture>
                <div class="explore_item_content">
                    <h4 class="explore_item_contenttitle"><?php echo $row["title"]; ?></h4>
                    <pre class="explore_item_contenttags"><?php if(!empty($tags)) echo getPrintableArray($tags); ?></pre>
                    <p><?php echo getFixedString(getCutString($row["notes"], $content_index_note_maxlength)); ?></p>
                </div>
            </a>
        </article>
    <?php } ?>
    </section>
</main>

<?php require_once($folder_include . "/footer.php"); ?>