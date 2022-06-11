<?php
$title = "➕ Crea contenuto";
$description = "Qui puoi creare un contenuto e mostrarlo a tutta la community.";
$tags = "create, ideas, photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
kickGuestUser();
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/create.css">
<script src="<?php echo $folder_scripts; ?>/create.js"></script>
<?php
require_once($folder_include . "/navbar.php");

// ottengo la data dell'ultima mia pubblicazione
$canCreate = false;

$stmt = $dbconn->prepare("SELECT creationDate FROM $table_usercontent WHERE userid = ? ORDER BY creationDate DESC LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$esito = $stmt->get_result();

if($esito->num_rows == 1) {
    $dati = $esito->fetch_assoc();
    $elapsedSinceLastPublication = time() - $dati["creationDate"];

    if($elapsedSinceLastPublication > $time_between_publications) {
        $canCreate = true;
    } else {
        $timeBeforeNextPublication = $time_between_publications - $elapsedSinceLastPublication;
    }
} else {
    $canCreate = true;
}
?>

<main class="main_content">
    <?php if(!$canCreate) { ?>
        <div class="flex_container">
            <div class="flex_item bgcolor_error color_on_error">
                <h2>Tempo rimasto alla prossima pubblicazione</h2>
                <h1><?php echo getFormattedTime($timeBeforeNextPublication); ?></b></h1>
                <p>
                    Grazie per voler pubblicare le tue creazioni ma, per evitare spam di contenuti,
                    è previsto un timer tra una pubblicazione e l'altra.
                </p>
                <?php print_goBackSection(); ?>
            </div>
        </div>
    <?php } else { ?>
        <p id="backend" class="gone"><?php echo $folder_backend; ?></p>
        <p id="titlemaxlength" class="gone"><?php echo $content_title_maxlength; ?></p>
        <p id="titleregex" class="gone"><?php echo $content_title_regex; ?></p>
        <p id="tagmaxlength" class="gone"><?php echo $content_tag_maxlength; ?></p>
        <p id="tagmaxnumber" class="gone"><?php echo $content_tag_maxnumber; ?></p>
        <p id="tagregex" class="gone"><?php echo $content_tag_regex; ?></p>
        <p id="maxcontentsize" class="gone"><?php echo $content_file_maxsize; ?></p>
        <p id="maxthumbnailsize" class="gone"><?php echo $content_thumbnail_maxsize; ?></p>

        <form id="uploadcontent_form" enctype="multipart/form-data" action="./<?php echo $folder_backend; ?>/uploadcontent.php" method="POST">
            <div id="intro" class="flex_container">
                <div class="flex_item bgcolor_primary color_on_primary">
                    <h1><?php echo $title; ?></h1>
                    <p>Ti diamo il benvenuto nella schermata per aggiungere un contenuto alla piattaforma.
                        I passaggi successivi compariranno man mano che i campi vengono compilati.</p>
                    <div class="arrow down arrow_small"></div>
                </div>
            </div>

            <div id="steps" class="flex_container"> <!-- passaggi -->
                <div id="step1" class="flex_item flex_container step_container bgcolor_primary color_on_primary">
                    <div class="flex_item step_item">
                        <h2>Passaggio 1</h2>
                        <h4>Quale categoria esprime meglio ciò che stai creando?</h4>
                        <select class="create_input" name="content_category" id="content_category" required>
                            <option value="">Specifica un categoria...</option>
                            <option value="photo">📸 Foto</option>
                            <option value="video">📹 Video</option>
                            <option value="drawing">🎨 Dipinto</option>
                            <option value="music">🎵 Musica</option>
                            <option value="text">✏ Testo</option>
                            <option value="poetry">📜 Poesia</option>
                        </select>

                        <h4>Titolo</h4>
                        <input class="create_input test-control" id="content_title" name="content_title" placeholder="Lunghezza massima: <?php echo $content_title_maxlength; ?> caratteri" maxlength="<?php echo $content_title_maxlength; ?>" pattern="<?php echo $content_title_regex; ?>" required />&nbsp;
                        <span id="content_title_result"></span>

                        <div class="arrow down arrow_small"></div>
                    </div>
                </div>

                <div id="step2" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                    <div class="flex_item step_item">
                        <h2>Passaggio 2</h2>
                        <h4>Carica il file principale da mostrare alla community.</h4>

                        <label id="content_label_file" for="content_file" class="content_file_upload">
                            ☁&nbsp;Carica risorsa
                        </label>
                        <input type="file" id="content_file" name="content_file" class="content_file" required>

                        <p>Tipi di file accettati: <code id="accepted_types"></code></p>
                        <p>Dimensione massima: <code><?php echo $content_file_maxsize / 1000000; ?>MB</code></p>
                        <hr>

                        <h4>Carica una miniatura per il contenuto che stai pubblicando.</h4>
                        <p>Per tutti i contenuti, tranne foto e dipinti, sarà visualizzata un'immagine di default se non carichi una miniatura.</p>
                        <label id="content_label_thumbnail" for="content_thumbnail" class="content_file_upload">
                            ☁&nbsp;Carica miniatura
                        </label>
                        <input type="file" id="content_thumbnail" name="content_thumbnail" class="content_file">

                        <p>Tipi di file accettati: <code id="accepted_types_thumbnail"></code></p>
                        <p>Dimensione massima: <code><?php echo $content_thumbnail_maxsize / 1000000; ?>MB</code></p>

                        <div class="arrow down arrow_small"></div>
                    </div>
                </div>

                <div id="step3" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                    <div class="flex_item step_item">
                        <h2>Passaggio 3</h2>
                        <h4>Tags</h4>
                        <div class="test">
                            <input class="create_input test-control" type="text" id="content_tags" name="content_tags" placeholder="tag1, tag2, tag3, ..."  maxlength="<?php echo $content_note_maxlength; ?>" pattern="<?php echo $content_tag_regex; ?>" /> <span id="content_tags_result"></span>
                        </div>
                        <h4>Note</h4>
                        <textarea class="create_input" id="content_notes" name="content_notes" placeholder="Lunghezza massima: <?php echo $content_note_maxlength;?> caratteri" rows="8" maxlength="<?php echo $content_note_maxlength; ?>"></textarea>
                        <br>
                        <h4>Altre opzioni</h4>
                        <input type="checkbox" id="content_setting_private" name="content_setting_private" value="1">
                        <label for="content_setting_private"> Vorrei che questo contenuto fosse visibile solo a chi mi segue.</label>

                        <div class="arrow down arrow_small"></div>
                    </div>
                </div>

                <div id="step4" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                    <div class="flex_item step_item">
                        <h2>Passaggio 4</h2>
                        <h4>Ricontrolla quello che hai inserito.</h4>
                        <input type="submit" name="button" class="button" value="📤 Pubblica" />
                        <p>Potrai pubblicare un nuovo contenuto tra <strong><?php echo getFormattedTime($time_between_publications); ?></strong>.</p>
                        <p>Ricorda che tutti i contenuti inviati devono rispettare i <a target="_blank" href="./legal.php?doc=tos">Termini di Servizio.</a></p>
                        <p id="uploadcontent_warning" class="color_warning gone"></p>
                    </div>
                </div>
            </div>
        </form>
    <?php } ?>
</main>

<?php require_once($folder_include . "/footer.php"); ?>