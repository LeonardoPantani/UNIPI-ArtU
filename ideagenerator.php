<?php
$title = "🔮 Generatore di idee";
$description = "A corto di idee? Questo generatore casuale di parole potrebbe darti qualche spunto per la creazione perfetta!";
$tags = "random generator, idea generator, photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/ideagenerator.css">
<script defer src="<?php echo $folder_scripts; ?>/ideagenerator.js"></script>
<?php require_once($folder_include . "/navbar.php"); ?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item bgcolor_primary color_on_primary">
            <h1><?php echo $title; ?></h1>
            <cite><?php echo $service_motto; ?></cite>
            <br><i class="arrow down arrow_small"></i>
        </div>
    </div>

    <div class="flex_container">
        <div class="flex_item bgcolor_primary color_on_primary">
            <div class="width_75" style="margin: auto;">
                <section class="slots">
                    <div id="slot1" class="icons">❓</div>
                    <div id="slot2" class="icons">❔</div>
                    <div id="slot3" class="icons">❓</div>
                </section>
                <!-- input -->
                <form id="form_idea" autocomplete="off" action="./<?php echo $folder_backend; ?>/gnrtidea.php" method="POST">
                    <select name="type" id="type" required>
                        <option value="">Che cosa?</option>
                        <option value="drawing">🎨 Dipinto</option>
                        <option value="music">🎵 Musica</option>
                        <option value="text">✏ Testo</option>
                        <option value="poetry">📜 Poesia</option>
                    </select>
                    <input type="submit" id="button_generate" class="button bgcolor_secondary color_on_secondary" value="🧙‍♂ Inizia" disabled />
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>