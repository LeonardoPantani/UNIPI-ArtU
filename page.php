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

$userpage = null;
if(isset($_GET["username"]) && $_GET["username"] != "") {
    $pageuserid = getUserIdByUsername($_GET["username"]);
    if($pageuserid != null) {
        $userpage = getPageById($pageuserid);
        $permission = canISeePage($userpage["userid"]);
        $amIFriend = false;
        if($permission) {
            $ratings = getRatings("page", $userpage["userid"]);

            $classButtonLike = "bgcolor_secondary_variant";
            $classButtonDislike = "bgcolor_secondary_variant";
            if(isLogged()) {
                $amIFriend = amIFriendOf($userpage["userid"]);

                $myRating = getUserRating("page", $id, $userpage["userid"]);
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
        <?php if($userpage == null) { ?>
            <div class="flex_item width_50 bgcolor_error color_on_error">
                <p>
                    Collegamento alla pagina dell'utente non valido.<br/>
                    Probabilmente siete arrivati qui da un link errato o avete inserito il nome utente a mano.<br/>
                    Lo schema corretto per visualizzare la pagina di un utente è: <code>pagina.php?username=*nomeutente*</code>
                </p>
                <br>
                <a href="./">🔙 Tornate alla homepage</a>
            </div>
        <?php } else { ?>
            <div class="flex_item bgcolor_primary color_on_primary">
                <div class="flex_container">
                    <div class="flex_item flexratio_40 textalign_end">
                        <img class="avatar avatar_big" src="<?php echo "./" . $folder_avatars . "/" . $userpage["avatarUri"]; ?>"  alt="Immagine profilo utente"/>
                    </div>
                    <div class="flex_item flexratio_60 textalign_start">
                        <h1><?php echo $userpage["username"]; ?></h1>
                        <?php
                            if(isLogged()) {
                                if($id == $userpage["userid"]) { ?>
                                    <p>Così è come vedranno gli altri utenti la pagina del vostro profilo. <a href="./editpage.php">🔧 Modifica pagina</a></p>
                                    <?php if(!$visibility) { ?>
                                    <p class="color_important">Nota: hai impostato la visibilità su <b>privata</b>, pertanto nessuno eccetto te può vedere questa pagina.</p>
                                    <?php } ?>
                                <?php } else {
                                    if($permission) { ?><p class="color_info">Il contenuto delle pagine utenti è definito esclusivamente da questi ultimi.<br/>In caso il testo violi i <a target="_blank" href="<?php echo "./legal.php?doc=pp"; ?>">Termini di Servizio</a> contattateci.</p><?php }

                                    if($amIFriend) {
                                        ?><a id="delfrndreq" href="./<?php echo $folder_backend; ?>/delfrnd.php?userid=<?php echo $userpage["userid"]; ?>">🙅‍♂ Rimuovi amicizia</a><?php
                                    } else {
                                        ?><a id="sndfrndreq" href="./<?php echo $folder_backend; ?>/sndfrndreq.php?userid=<?php echo $userpage["userid"]; ?>">📨 Invia richiesta di amicizia</a><?php
                                    }
                                }
                            } else { ?>
                            <p><a href="./auth.php">Accedi</a> per poter inviare una richiesta di amicizia a <b><?php echo $userpage["username"]; ?></b>.</p>
                        <?php } ?>
                        <!-- Meccanismo like e dislike -->
                        <?php if($permission) { ?>
                        <div class="rating_main">
                            <a class="changerating" id="changelike" href="./<?php echo $folder_backend; ?>/chngrtng.php?value=like&type=page&elementid=<?php echo $userpage["userid"]; ?>"><button id="like_button" class="button bgcolor_secondary_variant <?php echo $classButtonLike; ?> color_on_secondary" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="like_counter"><?php echo $ratings["likes"]; ?></span>] 👍 Mi piace</button></a>&nbsp;
                            <a class="changerating" id="changedislike" href="./<?php echo $folder_backend; ?>/chngrtng.php?value=dislike&type=page&elementid=<?php echo $userpage["userid"]; ?>"><button id="dislike_button" class="button bgcolor_secondary_variant <?php echo $classButtonDislike; ?> color_on_secondary" <?php if(!isLogged()) { echo "disabled"; } ?>>[<span id="dislike_counter"><?php echo $ratings["dislikes"]; ?></span>] 👎 Non mi piace</button></a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <hr>
                <?php if(!$permission) { ?>
                    <p class="color_important">Impossibile vedere altro, <b><?php echo $userpage["username"]; ?></b> non ha concesso a nessun altro di accedere alla sua pagina.</p>
                <?php } else { ?>
                    <div id="userpage">
                        <?php
                            if(empty($userpage["content"])) {
                                ?>La pagina di <b><?php echo $userpage["username"]; ?></b> è vuota.<?php
                            } else {
                                echo $userpage["content"];
                            }
                        ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
