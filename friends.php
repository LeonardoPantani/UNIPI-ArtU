<?php
$title = "Richieste di amicizia";
$description = "Qui puoi accettare o rifiutare richieste di amicizia di altri utenti.";
$tags = "photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<script src="<?php echo $folder_scripts; ?>/friends.js"></script>
<link rel="stylesheet" href="<?php echo $folder_css; ?>/pages/friends.css">
<?php
require_once($folder_include . "/navbar.php");

kickGuestUser();

$stmt = $dbconn->prepare("SELECT $table_friendrequests.*, $table_users.username FROM $table_friendrequests JOIN $table_users ON userida = $table_users.id WHERE useridb = ? AND status = 'pending'");
$stmt->bind_param("i", $id);
$stmt->execute();
$esito = $stmt->get_result();

$esitoFriends = getUserFriends($id);
?>

<main class="main_content">
    <div class="flex_container">
        <div class="flex_item width_50 bgcolor_primary color_on_primary">
            <h1>Richieste di amicizia</h1>
            <?php if($esito->num_rows > 0) { ?>
            <table id="table_frndreq">
                <tr>
                    <th>Nome utente</th>
                    <th>Data di invio</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    while ($row = $esito->fetch_assoc()) {
                        ?>
                        <tr id="frndreq<?php echo $row['id']; ?>">
                            <td><?php echo $row["username"]; ?></td>
                            <td><?php echo getFormattedDateTime($row["date"]); ?></td>
                            <td><a title="<?php echo $row['id']; ?>" class="frndreq_edit" href="./<?php echo $folder_backend; ?>/editfrndreq.php?req=<?php echo $row["id"]; ?>&code=accept">👍 Accetta</a></td>
                            <td><a title="<?php echo $row['id']; ?>" class="frndreq_edit" href="./<?php echo $folder_backend; ?>/editfrndreq.php?req=<?php echo $row["id"]; ?>&code=reject">👎 Rifiuta</a></td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
            <?php } else { ?>
                <p>Nessuna richiesta di amicizia in sospeso.</p>
            <?php } ?>
            <br>
            <hr>
            <h1>Amici</h1>
            <?php if($esitoFriends->num_rows > 0) { ?>
                <table class="color_on_secondary" id="table_friends">
                    <tr>
                        <th>Nome utente</th>
                        <th>Inizio amicizia</th>
                        <th></th>
                    </tr>
                    <?php
                    while ($row = $esitoFriends->fetch_assoc()) {
                        ?>
                        <tr id="friendid<?php echo $row["userid"]; ?>">
                            <td><?php echo $row["username"]; ?></td>
                            <td><?php echo getFormattedDateTime($row["date"]); ?></td>
                            <td><a title="<?php echo $row["userid"]; ?>" class="frndreq_del" href="./<?php echo $folder_backend; ?>/delfrnd.php?userid=<?php echo $row["userid"]; ?>">💔 Rimuovi amicizia</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php } else { ?>
                <p>Non avete amici.</p>
            <?php } ?>
            <br>
            <a href="./settings.php">🔙 Torna al tuo profilo</a>
        </div>
    </div>
</main>

<?php require_once($folder_include . "/footer.php"); ?>
