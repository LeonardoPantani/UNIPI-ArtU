<?php
$title = "🧑 Il tuo Profilo";
$description = "Qui puoi vedere il tuo profilo e fare modifiche.";
$tags = "";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
kickGuestUser();
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/profile.css">
<script src="<?php echo $folder_scripts; ?>/profile.js"></script>
<?php
require_once($folder_include . "/navbar.php");
$pendingfriendrequests = getNumPendingFriendRequests($id);
?>

<main class="main_content">
    <p id="username" class="gone"><?php echo $username; ?></p>
    <p id="defaulturi" class="gone"><?php if($defaultavatar_file == $avataruri) echo "true"; else echo "false"; ?></p>

    <div class="flex_container">
        <div class="flex_item bgcolor_primary color_on_primary">
            <div class="flex_container">
                <div class="flex_item flexratio_40 color_on_primary textalign_end">
                    <div id="avatar_container">
                        <img class="avatar avatar_big" src="<?php echo "./" . $folder_avatars . "/" . $avataruri; ?>"   alt="Immagine profilo"/>
                        <img id="avatar_edit" class="avatar avatar_big" src="<?php echo "./" . $folder_media . "/setprofileimage.jpg"; ?>" title="Cliccate per cambiare"  alt="Immagine profilo"/>
                        <button id="avatar_deletebutton" class="button bgcolor_secondary color_on_secondary avatar_delete invisible" type="button">Elimina</button>
                    </div>
                    <div id="chngimg_div" class="gone">
                        <form id="chngimg_form" enctype="multipart/form-data" action="./<?php echo $folder_backend; ?>/chngimg.php" method="POST">
                            <input type="file" id="avatarimginput" name="avatarimginput" accept="image/*" />
                            <input type="submit" name="button" value="Cambia foto" />
                        </form>
                    </div>
                </div>
                <div class="flex_item flexratio_60 color_on_primary textalign_start bold">
                    <h1>Che piacere rivederti, <a title="Aprite la tua pagina pubblica" target="_blank" href="./page.php?username=<?php echo $username; ?>"><span class="color_info"><?php echo $username; ?></span></a>!</h1>
                    <p>
                    <?php
                        if($pendingfriendrequests > 0) {
                            ?><a href="friends.php">📫 Hai <?php echo $pendingfriendrequests; ?> richiesta(e) in attesa. Clicca per vedere</a><?php
                        } else {
                            ?><a href="friends.php">📪 (<?php echo getFriendsNumber($id); ?>) Scheda Amici</a><?php
                        }
                    ?>
                    </p>
                </div>
            </div>
            <hr>
            <div class="textalign_start">
                <h2>ℹ Informazioni sul profilo</h2>
                <p>Indirizzo email: <code><?php echo $email; ?></code></p>
                <p>Data creazione: <b><?php echo getFormattedDate($creationDate); ?></b></p>
                <p>Visibilità pagina:
                    <span class="color_secondary">
                        <?php if ($setting_visibility) {
                            echo "Pubblica 👥";
                        } else {
                            echo "Privata 👤";
                        } ?>
                    </span>
                </p>
                <a href="./editpage.php">🔧 Cambia pagina profilo</a>
            </div>
            <hr>
            <div>
                <h2>🔑 Cambia password</h2>
                <p>Qui puoi effettuare il cambio della password. Ricorda: deve essere lunga almeno <?php echo $password_minlength; ?> caratteri.</p>
                <form id="chngpswd_form" autocomplete="off" action="./<?php echo $folder_backend; ?>/chngpswd.php" method="POST">
                    <!-- la vecchia password non ha il requisito minlength nel caso in cui si cambiasse la dimensione minima in futuro -->
                    <input autocomplete="new-password" type="password" id="oldpassword" name="oldpassword" placeholder="Vecchia password"><br>
                    <input autocomplete="new-password" type="password" id="newpassword" name="newpassword" placeholder="Nuova password" minlength="<?php echo $password_minlength; ?>"><br>

                    <input id="chngpswd_submitform" type="submit" value="Cambia password" class="button bgcolor_secondary color_on_secondary" disabled />

                    <p id="chngpswd_warning" class="color_warning gone"></p>
                </form>

                <h2>🔖 Paginazione</h2>
                <p>Qui puoi specificare quanti contenuti degli utenti vedere per pagina. Per ora si applica solo alla sezione <b>🌍 Esplora</b>.</p>
                <form id="chngpgntn_form" autocomplete="off" action="./<?php echo $folder_backend; ?>/chngpgnt.php" method="POST">
                    <p id="numelemsperpage" class="gone"><?php echo $setting_numElemsPerPage; ?></p>
                    <select name="chngpgnt_numElemsPerPage" id="chngpgnt_numElemsPerPage">
                        <option value="<?php echo $setting_numElemsPerPage; ?>">Selezionato: <?php echo $setting_numElemsPerPage; ?> elementi per pagina</option>
                        <?php
                            foreach($validPaginationNumbers as $value) {
                                if($value != $setting_numElemsPerPage) { ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?> elementi per pagina</option>
                                <?php } ?>
                        <?php } ?>
                    </select><br>

                    <input id="chngpgnt_submitform" type="submit" value="Cambia paginazione" class="button bgcolor_secondary color_on_secondary" disabled />

                    <p id="chngpgnt_warning" class="color_warning gone"></p>
                </form>
            </div>
            <hr>
            <div>
                <h2>❌ Zona pericolosa</h2>
                <div class="flex_container">
                    <div class="flex_item flexratio_50 textalign_start">
                        <h3 class="color_important uppercase">👻 Cambia visibilità</h3>
                        <p>In modalità privata la pagina non sarà visibile al pubblico. Potrai continuare a navigare su <b><?php echo $service_name; ?></b>. In modalità pubblica, tutti potranno vedere la tua pagina.</p>
                    </div>

                    <div class="flex_item flexratio_50">
                        <button id="chngprofvis_button" name="chngprofvis_button" class="button bgcolor_secondary color_on_secondary">Inizia cambio</button>

                        <div id="chngprofvis_div" class="gone">
                            <form id="chngprofvis_form" autocomplete="off" action="./<?php echo $folder_backend; ?>/chngprofvis.php" method="POST">
                                <h4>Scrivi il tuo nome utente e premi su <span class="color_info">Cambia visibilità</span></h4>
                                <input type="text" id="chngprofvis_text" name="chngprofvis_text" placeholder="Qual è il tuo nome utente?"><br>
                                <br>
                                <input id="chngprofvis_cancel" type="button" value="Annulla" class="button bgcolor_secondary color_on_secondary" />
                                <input id="chngprofvis_submitform" type="submit" value="Cambia visibilità" class="button bgcolor_secondary color_on_secondary" disabled />

                                <p id="chngprofvis_warning" class="color_warning gone"></p>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex_container">
                    <div class="flex_item flexratio_50 textalign_start">
                        <h3 class="color_important uppercase">🧨 Elimina account</h3>
                        <p>ATTENZIONE: questa operazione non è reversibile. Forse vorresti cambiare la visibilità del tuo profilo? Se invece desideri davvero eliminare l'account, segui la procedura.</p>
                    </div>

                    <div class="flex_item flexratio_50">
                        <button id="delacc_button" name="delacc_button" class="button bgcolor_secondary color_on_secondary">Inizia eliminazione</button>
                        <div id="delacc_div" class="gone">
                            <form id="delacc_form" autocomplete="off" action="./<?php echo $folder_backend; ?>/delacc.php" method="POST">
                                <h4>Scrivi il tuo nome utente e premi su <span class="color_info">Elimina account</span></h4>
                                <input type="text" id="delacc_text" name="delacc_text" placeholder="Qual è il tuo nome utente?"><br>
                                <br>
                                <input id="delacc_cancel" type="button" value="Annulla" class="button bgcolor_secondary color_on_secondary" />
                                <input id="delacc_submitform" type="submit" value="Elimina account" class="button bgcolor_secondary color_on_secondary" disabled />

                                <p id="delacc_warning" class="color_warning gone"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>