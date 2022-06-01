<?php
$title = "Autenticazione";
$description = "Accedi o registrati da questa pagina.";
$tags = "";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
kickLoggedUser();
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/auth.css">
<script src="<?php echo $folder_scripts; ?>/auth.js"></script>
<?php require_once($folder_include . "/navbar.php");
?>

<div class="main_content">
    <div id="auth_grid_container" class="align_center bgcolor_important color_on_info width_50 bold" style="background-image: url('./media/auth1.webp');">
        <div class="auth_grid_item_welcome" style="grid-area: welcome;">Benvenuti</div>
        <div class="auth_grid_item_welcome" style="grid-area: hello;">Bentornati</div>
        <div style="grid-area: empty;"></div>
        <div class="auth_grid_item_motto" style="grid-area: motto;"><?php echo $service_motto; ?>   </div>
    </div>

    <div class="flex_container width_50 align_center">
        <div id="dialog" class="flex_item flexratio_50 bgcolor_error color_on_error gone">
            <p id="dialog_text"></p>
        </div>
    </div>

    <div class="flex_container width_50 align_center">
        <div class="flex_item flexratio_50 bgcolor_primary color_on_primary">
            <h1>Registratevi</h1>
            <form id="form_register" autocomplete="off" action="./<?php echo $folder_backend; ?>/dbauth.php" method="POST">
                <input type="text" name="username" placeholder="Nome utente"><br>

                <input type="email" name="email" placeholder="Email"><br>

                <input type="password" name="password" placeholder="Password"><br>

                <input type="password" name="repeatpassword" placeholder="Ripeti password">
                <br><br>
                <input type="submit" value="Registrazione" class="button bgcolor_secondary color_on_secondary">
                <p><small>Registrandoti accetti l'<a target="_blank" href="<?php echo "./" . $folder_media . "/privacypolicy.txt"; ?>">informativa sulla privacy</a> di <b><?php echo $service_name; ?></b>.</small></p>
            </form>
        </div>
        <div class="flex_item flexratio_50 bgcolor_primary color_on_primary">
            <h1>Accedete</h1>
            <form id="form_login" autocomplete="on" action="./<?php echo $folder_backend; ?>/dbauth.php" method="POST">
                <input type="text" name="access" placeholder="Nome utente o Email"><br>

                <input type="password" name="password" placeholder="Password"><br>

                <br>
                <input type="submit" value="Login" class="button bgcolor_secondary color_on_secondary">
            </form>
        </div>
    </div>
</div>
<?php require_once($folder_include . "/footer.php"); ?>