<?php
$title = "Richieste di amicizia";
$description = "Qui puoi accettare o rifiutare richieste di amicizia di altri utenti.";
$tags = "photo, video, drawing, music";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php"); ?>
<script src="<?php echo $folder_scripts; ?>/friendrequests.js"></script>
<?php
require_once($folder_include . "/navbar.php");

kickGuestUser();

$stmt = $dbconn->prepare("SELECT $table_friendrequests.*, $table_users.username FROM $table_friendrequests JOIN $table_users ON useridb = $table_users.id WHERE userida = ? AND status = 'pending'");
$stmt->bind_param("i", $id);
$stmt->execute();
$esito = $stmt->get_result();

// TODO applicare layout alla tabella
?>

<div class="main_content">
    <div class="flex_container">
        <div class="flex_item width_50 bgcolor_primary color_on_primary">
            <h1>Richieste di amicizia</h1>
            <?php if($esito->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>Nome utente</th>
                    <th>Data di invio</th>
                    <th>...</th>
                </tr>
                <?php
                    while ($row = $esito->fetch_assoc()) {
                        ?>
                        <tr id="frndreq<?php echo $row['id']; ?>">
                            <td><?php echo $row["username"]; ?></td>
                            <td><?php echo getFormattedDate($row["date"]); ?></td>
                            <td><a title="<?php echo $row['id']; ?>" class="frndreq_accept" href="./<?php echo $folder_backend; ?>/acptfrndreq.php?req=<?php echo $row["id"]; ?>">👍</a>&nbsp;&nbsp;<a title="<?php echo $row['id']; ?>" class="frndreq_reject" href="./<?php echo $folder_backend; ?>/rejfrndreq.php?req=<?php echo $row["id"]; ?>">👎</a></td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
            <?php } else { ?>
                <p>Nessuna richiesta di amicizia in sospeso.</p>
            <?php } ?>
            <br>
            <a href="./settings.php">🔙 Torna al tuo profilo</a>
        </div>
    </div>
</div>

<?php require_once($folder_include . "/footer.php"); ?>
