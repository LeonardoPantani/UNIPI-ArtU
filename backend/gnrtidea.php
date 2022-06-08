<?php
require_once("../config/config.php");
require_once("../" . $folder_include . "/functions.php");
require_once("../" . $folder_include . "/dbconn.php");

if(!isset($_POST["type"]) && $_POST["type"] === "") {
    echo _("Dati invalidi.");
    return;
}

switch($_POST["type"]) {
    case "drawing": {
        $partA = ["una casa", "un palazzo", "un paese", "una città", "un quartiere", "un castello"];
        $partB = ["di pietra", "di legno", "di mattoni", "di cemento", "di lava", "d'acqua"];
        $partC = ["in stile gotico", "in stile romanico", "in stile barocco", "in stile neo-classico", "in stile classico", "in stile moderno"];
        break;
    }
    case "music": {
        $partA = ["una canzone cantata", "una canzone solo strumenti", "una canzone in coro", "una canzone singola"];
        $partB = ["Blues", "metal", "country", "folk", "rock", "pop", "hard core", "industrial", "jazz", "classica", "punk", "trash"];
        $partC = ["a ritmo largo", "a ritmo lento", "a ritmo andante", "a ritmo allegro", "a ritmo presto", "a ritmo vivace"];
        break;
    }
    case "text": {
        $partA = ["un racconto", "un breve testo", "un testo argomentativo", "un testo informativo"];
        $partB = ["riguardo"];
        $partC = ["la guerra", "l'amore", "l'odio", "la tristezza", "la gioia", "un cane", "un gatto", "un piccione", "un vulcano", "un abisso", "il sole", "la luna", "gli Stati Uniti", "l'Italia", "la Germania", "la Francia", "la Spagna", "il Giappone", "la Cina", "un Abu"];
        break;
    }
    case "poetry": {
        $partA = ["un sonetto", "una ballata", "una ode", "una villanella", "una madrigale", "una rima reale", "una sonora", "una olistica", "una videopoesia", "un verso"];
        $partB = ["riguardo"];
        $partC = ["la guerra", "l'amore", "l'odio", "la tristezza", "la gioia", "un cane", "un gatto", "un piccione", "un vulcano", "un abisso", "il sole", "la luna", "gli Stati Uniti", "l'Italia", "la Germania", "la Francia", "la Spagna", "il Giappone", "la Cina", "un Abu"];
        break;
    }
}


$ret = array(
    0 => $partA,
    1 => $partB,
    2 => $partC
);

echo json_encode($ret);