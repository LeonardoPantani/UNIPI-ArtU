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
<?php
require_once($folder_include . "/navbar.php");

if(!isset($_GET["username"]) || $_GET["username"] == "") {
    $pageuserdata = null;
    $userpage = null;
}  else {
    $pageuserdata = getUserDataByUsername($_GET["username"]);
    $amIFriend = false;
    $canISeeContent = false;
    if($pageuserdata != null) {
        $userpage = getPageById($pageuserdata["id"]);
        if(isLogged()) {
            $amIFriend = amIFriendOf($id, $pageuserdata["id"]);
        }

        if($pageuserdata["visibility"] == 1 || (isLogged() && ($amIFriend || $id == $pageuserdata["id"]))) {
            $canISeeContent = true;
        }
    }
}
?>

<main class="main_content">
    <div class="flex_container">
        <?php if($pageuserdata == null) { ?>
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
                        <img class="avatar avatar_big" src="<?php echo "./" . $folder_avatars . "/" . $pageuserdata["avatarUri"]; ?>"  alt="Immagine profilo utente"/>
                    </div>
                    <div class="flex_item flexratio_60 textalign_start">
                        <h1><?php echo $pageuserdata["username"]; ?></h1>
                        <?php
                            if(isLogged()) {
                                if($id == $pageuserdata["id"]) { ?>
                                    <p>Così è come vedranno gli altri utenti la pagina del vostro profilo. <a href="./editpage.php">🔧 Modifica pagina</a></p>
                                    <?php if(!$visibility) { ?>
                                    <p class="color_important">Nota: avete impostato la visibilità del profilo su <b>privata</b>, pertanto nessuno eccetto voi può vedere questa pagina.</p>
                                    <?php } ?>
                                <?php } else {
                                    if($canISeeContent) { ?><p>Il contenuto delle pagine utenti è definito esclusivamente da questi ultimi.<br/>In caso il testo violi i <a target="_blank" href="<?php echo "./legal.php?doc=pp"; ?>">Termini di Servizio</a> contattateci.</p><?php }

                                    if($amIFriend) {
                                        ?><a id="delfrndreq" href="./<?php echo $folder_backend; ?>/delfrnd.php?userid=<?php echo $pageuserdata['id']; ?>">🙅‍♂ Rimuovi amicizia</a><?php
                                    } else {
                                        ?><a id="sndfrndreq" href="./<?php echo $folder_backend; ?>/sndfrndreq.php?userid=<?php echo $pageuserdata['id']; ?>">📨 Invia richiesta di amicizia</a><?php
                                    }
                                }
                            } ?>
                    </div>
                </div>
                <hr>
                <?php if(!$canISeeContent) { ?>
                    <p>Impossibile vedere altro, <b><?php echo $pageuserdata["username"]; ?></b> non ha concesso a nessun altro di accedere alla sua pagina.</p>
                <?php } else { ?>
                    <div id="userpage">
                        <?php
                            if($userpage == null || empty($userpage["content"])) {
                                ?>La pagina di <b><?php echo $pageuserdata["username"]; ?></b> è vuota.<?php
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
