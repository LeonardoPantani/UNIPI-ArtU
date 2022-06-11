<?php $title = "📃 Pagina utente";
$description = "Questa è la pagina personalizzata di un utente.";
$tags = "custompage";
require_once("config/config.php");
require_once($folder_include . "/dbconn.php");
require_once($folder_include . "/functions.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/page.css">
<script src="<?php echo $folder_scripts; ?>/page.js"></script>

<link rel="stylesheet" href="<?php echo $folder_css; ?>/rating_system.css">
<script src="<?php echo $folder_scripts; ?>/rating_system.js"></script>
<?php require_once($folder_include . "/navbar.php");
$paginationNumber = 3; // TODO rendere configurabile

$userpage = null;
$permission = false;
$emptypage = true;
if(isset($_GET["username"]) && $_GET["username"] != "") {
    $pageuserid = getUserIdByUsername($_GET["username"]);
    if($pageuserid != null) { // se l'utente esiste si entra nell'if
        $userpage = getUserPageById($pageuserid);
        if(isset($userpage["content"]) && $userpage["content"] != "") {
            $emptypage = false;
        }

        $permission = canISeePage($userpage["id"]);
        $amIFriend = false;
        $visibleContent = getUserContent($pageuserid, 0, $paginationNumber); // contiene i contenuti dell'utente visibili a tutti
        $invisibleContent = null; // contiene i contenuti dell'utente visibili ai soli amici
        if($permission) {
            $ratings = getRatings("page", $userpage["userid"]);

            $classButtonLike = "bgcolor_secondary_variant";
            $classButtonDislike = "bgcolor_secondary_variant";
            if(isLogged()) {
                $amIFriend = amIFriendOf($pageuserid);
                if($amIFriend || $id == $pageuserid) {
                    $invisibleContent = getUserContent($pageuserid, 1, $paginationNumber);
                }

                $myRating = getUserRating("page", $id, $pageuserid);
                if($myRating == 1) { // like
                    $classButtonLike = "chosenrating";
                } else if($myRating == 0) { // dislike
                    $classButtonDislike = "chosenrating";
                }
            }
        }
    }
}
?>

<main class="main_content">
    <div class="flex_container">
    <?php if(!isset($userpage["username"]) || $pageuserid == null) { ?>
        <div class="flex_item bgcolor_error color_on_error">
            <p>
                Collegamento alla pagina dell'utente non valido.<br/>
                Probabilmente siete arrivati qui da un link errato o avete inserito il nome utente a mano.<br/>
                Lo schema corretto per visualizzare la pagina di un utente è: <code>pagina.php?username=*nomeutente*</code>
            </p>
            <?php print_goBackSection(); ?>
        </div>
    <?php } else { ?>
        <div class="flex_item bgcolor_primary color_on_primary">
            <div class="flex_container">
                <div class="flex_item flexratio_40 textalign_end">
                    <img class="avatar avatar_big" src="<?php echo "./" . $folder_avatars . "/" . getAvatarUri($userpage["avatarUri"]); ?>"  alt="Immagine profilo utente"/>
                </div>
                <div class="flex_item flexratio_60 textalign_start">
                    <h1><?php echo $userpage["username"]; ?></h1>
                    <?php
                        if(isLogged()) {
                            if($id == $pageuserid) { ?>
                                <p>Così è come vedranno gli altri utenti la tua pagina del profilo. <a href="./editpage.php">🔧 Modifica pagina</a></p>
                                <?php if(!$visibility) { ?>
                                <p class="color_important">Nota: hai impostato la visibilità della pagina su <b>privata</b>, pertanto nessuno eccetto te e i tuoi amici potrà vederla.</p>
                                <?php } ?>
                            <?php } else {
                                if($permission) { ?><p class="color_info">Il contenuto delle pagine utenti è definito esclusivamente da questi ultimi.<br/>In caso il testo violi i <a target="_blank" href="<?php echo "./legal.php?doc=pp"; ?>">Termini di Servizio</a> contattateci.</p><?php }

                                if($amIFriend) {
                                    ?><a id="delfrndreq" href="./<?php echo $folder_backend; ?>/delfrnd.php?userid=<?php echo $pageuserid; ?>">🙅‍♂ Rimuovi amicizia</a><?php
                                } else {
                                    ?><a id="sndfrndreq" href="./<?php echo $folder_backend; ?>/sndfrndreq.php?userid=<?php echo $pageuserid; ?>">📨 Invia richiesta di amicizia</a><?php
                                }
                            }
                        } else { ?>
                        <p><a href="./auth.php">Accedi</a> per poter inviare una richiesta di amicizia a <b><?php echo $userpage["username"]; ?></b>.</p>
                    <?php } ?>
                    <!-- Meccanismo like e dislike -->
                    <?php if($permission && !$emptypage) {
                        print_ratingSection($myRating, $ratings, "page", $pageuserid);
                    } ?>
                </div>
            </div>
            <hr>
            <?php if(!$permission) { ?>
                <p class="color_important">Impossibile vedere altro, <b><?php echo $userpage["username"]; ?></b> non ha concesso a nessun altro di accedere alla sua pagina.</p>
            <?php } else { ?>
                <div class="section_description textalign_start">
                    <p>
                        <?php
                            if($emptypage) {
                                ?>La pagina di <b><?php echo $userpage["username"]; ?></b> è vuota.<?php
                            } else {
                                echo nl2br($userpage["content"]);
                            }
                        ?>
                    </p>
                </div>
            <?php } ?>

            <?php print_goBackSection(); ?>
        </div>

        <div class="flex_item bgcolor_primary color_on_primary">
            <h2>Contenuti pubblici di <?php echo $userpage["username"]; ?></h2>
            <div class="explore_container textalign_center">
                <?php print_contents($visibleContent); ?>
            </div>
        </div>

        <div class="flex_item bgcolor_primary color_on_primary">
            <h2>Contenuti privati di <?php echo $userpage["username"]; ?></h2>
            <?php if($amIFriend || $id == $pageuserid) { ?>
                <div class="explore_container textalign_center">
                    <?php print_contents($invisibleContent); ?>
                </div>
            <?php } else {
                if(isLogged()) { ?>
                    <p>Te e <b><?php echo $userpage["username"]; ?></b> non siete amici.</p>
                <?php } else { ?>
                    <p>Non hai effettuato l'accesso.</p>
            <?php } } ?>
        </div>
    <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
