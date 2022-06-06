<?php
require_once("config/config.php");

header("Content-Type: text/plain; charset=utf-8");
if(isset($_GET["doc"])) {
    if($_GET["doc"] == "pp") {
        readfile("./" . $folder_media . "/pp.txt");
    } else {
        readfile("./" . $folder_media . "/tos.txt");
    }
} else {
    echo _("Specificare il documento da leggere così: legal.php?doc=*documento*");
}