<?php
$title = "🚪 Autenticazione";
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

<main class="main_content">
    <div id="auth_grid_container" class="align_center bgcolor_important color_on_info width_50 bold" style="background-image: url('./media/auth1.webp');">
        <div class="auth_grid_item_welcome" style="grid-area: welcome;">Benvenuti</div>
        <div class="auth_grid_item_welcome" style="grid-area: hello;">Bentornati</div>
        <div style="grid-area: empty;"></div>
        <div class="auth_grid_item_motto" style="grid-area: motto;"><?php echo $service_motto; ?>   </div>
    </div>

    <div class="flex_container width_50 align_center">
        <div id="dialog" class="flex_item bgcolor_error color_on_error gone">
            <p id="dialog_text"></p>
        </div>
    </div>

    <div class="flex_container width_50 align_center">
        <div class="flex_item bgcolor_primary bgcolor_secondary textalign_start">
            <h3 class="textalign_center">Restrizioni</h3>
            <ul>
                <li>Username ➡ tra i 6 e i 20 caratteri, accettati: lettere, numeri, trattini bassi</li>
                <li>Password ➡ almeno 6 caratteri</li>
            </ul>
        </div>
    </div>

    <p id="usernameregex" class="gone"><?php echo $username_regex; ?></p>
    <div class="flex_container width_50 align_center">
        <div class="flex_item flexratio_50 bgcolor_primary color_on_primary">
            <h1>🍀 Registrati</h1>
            <form class="form_auth" autocomplete="off" action="./<?php echo $folder_backend; ?>/dbauth.php" method="POST">
                <input type="text" class="register_validation" id="register_username" name="username" placeholder="Nome utente" pattern="<?php echo $username_regex; ?>" required /><br>
                <input type="email" class="register_validation" id="register_email"  name="email" placeholder="Email" required /><br>
                <input type="password" class="register_validation" id="register_password" name="password" placeholder="Password" minlength="<?php echo $password_minlength; ?>" required /><br>
                <input type="password" class="register_validation" id="register_repeatpassword" name="repeatpassword" placeholder="Ripeti password" minlength="<?php echo $password_minlength; ?>" required /><br>
                <input type="submit" id="register_submit" value="Registrazione" class="button bgcolor_secondary color_on_secondary" disabled>
                <p><small>Registrandoti accetti l'<a target="_blank" href="<?php echo "./legal.php?doc=pp"; ?>">informativa sulla privacy</a> e i <a target="_blank" href="<?php echo "./legal.php?doc=tos"; ?>">Termini di Servizio</a> di <b><?php echo $service_name; ?></b>.</small></p>
            </form>
        </div>
        <div class="flex_item flexratio_50 bgcolor_primary color_on_primary">
            <h1>🍁 Accedi</h1>
            <form class="form_auth" autocomplete="on" action="./<?php echo $folder_backend; ?>/dbauth.php" method="POST">
                <input type="text" class="login_validation" id="login_access" name="access" placeholder="Nome utente o Email" required><br>
                <input type="password" class="login_validation" id="login_password" name="password" placeholder="Password" minlength="<?php echo $password_minlength; ?>" required><br>
                <input type="submit" id="login_submit" value="Login" class="button bgcolor_secondary color_on_secondary" disabled>
            </form>
        </div>
    </div>
</main>
<?php require_once($folder_include . "/footer.php"); ?>