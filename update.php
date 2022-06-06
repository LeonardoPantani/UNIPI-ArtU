<?php
$title = "🖊 Modifica contenuto";
$description = "Qui puoi modificare un precedente contenuto che hai pubblicato.";
$tags = "update, edit, ideas, photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
kickGuestUser();
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/create.css">
<script src="<?php echo $folder_scripts; ?>/update.js"></script>
<?php
require_once($folder_include . "/navbar.php");

// TODO lavorare alla update, campi aggiornabili: notes, tags, settings
?>

<main class="main_content">
    <p id="backend" class="gone"><?php echo $folder_backend; ?></p>
    <p id="titlemaxlength" class="gone"><?php echo $content_title_maxlength; ?></p>
    <p id="titleregex" class="gone"><?php echo $content_title_regex; ?></p>
    <p id="tagmaxlength" class="gone"><?php echo $content_tag_maxlength; ?></p>
    <p id="tagmaxnumber" class="gone"><?php echo $content_tag_maxnumber; ?></p>
    <p id="tagregex" class="gone"><?php echo $content_tag_regex; ?></p>

    <form id="uploadcontent_form" enctype="multipart/form-data" action="./<?php echo $folder_backend; ?>/uploadcontent.php" method="POST">
        <div id="intro" class="flex_container">
            <div class="flex_item width_50 bgcolor_primary color_on_primary">
                <h1><?php echo $title; ?></h1>
                <cite><?php echo $service_motto; ?></cite>
                <br><i class="arrow down arrow_small"></i>
            </div>
        </div>

        <div id="steps" class="flex_container"> <!-- passaggi -->
            <div id="step1" class="flex_item flex_container step_container bgcolor_primary color_on_primary">
                <div class="flex_item flexratio_80 step_item">
                    <h2>Passaggio 1</h2>
                    <h4>Quale categoria esprime meglio ciò che state creando?</h4>
                    <select class="create_input" name="content_category" id="content_category" required>
                        <option value="">Specificare un categoria...</option>
                        <option value="photo">Foto</option>
                        <option value="video">Video</option>
                        <option value="drawing">Dipinto</option>
                        <option value="music">Musica</option>
                        <option value="text">Testo</option>
                        <option value="poetry">Poesia</option>
                    </select>

                    <h4>Titolo</h4>
                    <div class="test">
                        <input class="create_input test-control" id="content_title" name="content_title" placeholder="Lunghezza massima: <?php echo $content_title_maxlength; ?> caratteri" required /> <span id="content_title_result"></span>
                    </div>
                </div>
                <div class="flex_item flexratio_20 flexitem_arrow_next">
                    <i class="arrow right arrow_small"></i>
                </div>
            </div>

            <div id="step2" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                <div class="flex_item flexratio_80 step_item">
                    <h2>Passaggio 2</h2>
                    <h4>Carica il file principale da mostrare alla community.</h4>

                    <label id="content_label_file" for="content_file" class="content_file_upload">
                        ☁&nbsp;Carica risorsa
                    </label>
                    <input type="file" id="content_file" name="content_file" class="content_file" required>

                    <p>Tipi di file accettati: <code id="accepted_types"></code></p>

                    <h4>Carica una miniatura per il contenuto che stai pubblicando.</h4>
                    <p>Per tutti i contenuti, tranne foto e dipinti, sarà visualizzata un'immagine di default se non carichi una miniatura.</p>
                    <label id="content_label_thumbnail" for="content_thumbnail" class="content_file_upload">
                        ☁&nbsp;Carica miniatura
                    </label>
                    <input type="file" id="content_thumbnail" name="content_thumbnail" class="content_file">

                    <p>Tipi di file accettati: <code id="accepted_types_thumbnail"></code></p>
                </div>
                <div class="flex_item flexratio_20 flexitem_arrow_next">
                    <i class="arrow right arrow_small"></i>
                </div>
            </div>

            <div id="step3" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                <div class="flex_item flexratio_80 step_item">
                    <h2>Passaggio 3</h2>
                    <h4>Tags</h4>
                    <div class="test">
                        <input class="create_input test-control" type="text" id="content_tags" name="content_tags" placeholder="tag1, tag2, tag3, ..."  maxlength="<?php echo $content_note_maxlength; ?>" /> <span id="content_tags_result"></span>
                    </div>
                    <h4>Note</h4>
                    <textarea class="create_input" id="content_notes" name="content_notes" placeholder="Lunghezza massima: <?php echo $content_note_maxlength;?> caratteri" rows="8" maxlength="<?php echo $content_note_maxlength; ?>"></textarea>
                    <br>
                    <h4>Altre opzioni</h4>
                    <input type="checkbox" id="content_setting_private" name="content_setting_private" value="1">
                    <label for="content_setting_private"> Vorrei che questo contenuto fosse visibile solo a chi mi segue.</label><br>
                </div>
                <div class="flex_item flexratio_20 flexitem_arrow_next">
                    <i class="arrow right arrow_small"></i>
                </div>
            </div>

            <div id="step4" class="flex_item flex_container step_container step_visibility bgcolor_primary color_on_primary">
                <div class="flex_item step_item">
                    <h2>Passaggio 4</h2>
                    <h4>Ricontrollate quello che avete inserito.</h4>
                    <p>Ricorda che tutti i contenuti inviati devono rispettare i <a target="_blank" href="./legal.php?doc=tos">Termini di Servizio.</a></p>
                    <input type="submit" name="button" class="button" value="Invia" />
                    <p id="uploadcontent_warning" class="gone"></p>
                </div>
            </div>
        </div>
    </form>
</main>

<?php require_once($folder_include . "/footer.php"); ?>