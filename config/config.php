<?php
/**
 * File di configurazione. Contiene alcune variabili modificabili e altre che indicano i percorsi dei vari componenti del sito.
 */
$service_name = "ArtU";
$service_motto = "Fai qualcosa di straordinario";
$service_version = "20220612";
$defaultavatar_file = "default.jpg";
$defaultcontent_file = "default.jpg";

$debug_mode = false; // se vero ignora la password al login
$error_reporting = 0; // se 0 non mostra errori di nessun tipo

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_dbname = "Pantani_ArtU";

$table_users = "users";
$table_usercontent = "usercontent";
$table_usercontent_ratings = "usercontent_ratings";
$table_usercontent_comments = "usercontent_comments";
$table_friends = "friends";
$table_friendrequests = "friendrequests";
$table_pages = "pages";
$table_pages_ratings = "page_ratings";

$folder_backend = "backend";
$folder_include = "include";
$folder_libraries = "libraries";
$folder_css = "css";
$folder_scripts = "scripts";
$folder_media = "media";

$username_regex = '^\w{6,20}$'; // regex per l'username, accetta tutte le words (parole) tra i 6 e i 20 caratteri
$password_minlength = 6;

$validPaginationNumbers = [5, 15, 30, 50, 100]; // numeri validi elementi di ogni pagina

$time_between_publications = 0; // in secondi, tempo minimo tra una pubblicazione e l'altra

$time_between_comments = 0; // in secondi, tempo minimo tra un commento e l'altro

$explore_note_maxlength = 150; // massima lunghezza delle note

$content_page_maxlength = 5000; // lunghezza massima pagina pubblica di ogni utente
$content_text_view_maxlength = 3000; // lunghezza massima di testo visibile nella pagina view.php
$comment_maxlength = 500; // lunghezza massima di un commento

// massimi tag 30, lunghezza totale singolo tag 20, con aggiunto virgola e spazio 22, totale: 660 caratteri possibili (700 nel database per sicurezza)
$content_tag_maxlength = 20;
$content_tag_maxnumber = 30;
/*
    spiegazione regex:
    ^ inizio valutazione
    $ fine valutazione
    [a-zA-Z_]+ insieme di 1 o più caratteri dell'insieme:
        lettere tra la a e z (maiusc. e minusc.)
        trattini bassi
    (?=(,?\s*)) fa match una virgola seguita da 0 o più spazi
    (?:\1[a-zA-Z_]+)+ se il gruppo precedente ha successo, fa match con lettere e trattini bassi solo se ne viene definito un insieme di 1 o più

    graficamente visibile su:
    https://jex.im/regulex/#!embed=true&flags=&re=%5E%5Ba-zA-Z_%5D%2B(%3F%3D(%2C%3F%5Cs*))(%3F%3A%5C1%5Ba-zA-Z_%5D%2B)%2B%24
 */
$content_tag_regex = '^[a-zA-Z_]+(?=(,?\s*))(?:\1[a-zA-Z_]+)+$';

/*
 * Questa regex consente i seguenti caratteri:
 * A-Z e a-z
 * lettere accentate maiuscole e minuscole
 * spazi
 * i seguenti simboli: , . ; : - ! ? * ( )
 */
$content_title_regex = "^[\wÀ-ú ,.;:\-!?*()]+$";
$content_title_maxlength = 200;
$content_note_maxlength = 3000;
$content_file_maxsize = 30000000; // 30 mb = 30 milioni di byte
$content_thumbnail_maxsize = 15000000; // 15mb = 15 milioni di byte

$usercontent_types = ["photo", "video", "drawing", "music", "text", "poetry"];
$usercontent_directlyviewable = ["photo", "drawing"];

$folder_usercontent = "usercontent";
$folder_avatars = $folder_usercontent . "/" . "avatars";
$folder_photo = $folder_usercontent . "/" . "photo";
$folder_video = $folder_usercontent . "/" . "video";
$folder_drawing = $folder_usercontent . "/" . "drawing";
$folder_music = $folder_usercontent . "/" . "music";
$folder_text = $folder_usercontent . "/" . "text";
$folder_poetry = $folder_usercontent . "/" . "poetry";
$folder_thumbnail = $folder_usercontent . "/" . "thumbnail";

$accept_photo     = ["jpg","jpeg","png","webp","bmp","ico","svg","gif"];
$accept_video     = ["mp4","mov","avi","webm","mkv"];
$accept_drawing   = ["jpg","jpeg","png","webp","bmp","ico","svg","gif"];
$accept_music     = ["m4a","flac","mp3","wav","wma","aac","midi"];
$accept_text      = ["doc", "docx", "pdf", "txt"];
$accept_poetry    = ["doc", "docx", "pdf", "txt"];
$accept_thumbnail = ["jpg","jpeg","png","webp","bmp","ico","svg","gif"];

$loremipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ultrices, turpis sed cursus ultricies, orci nulla varius enim, non scelerisque justo massa ornare justo. Praesent ut suscipit nisi, at ultrices dui. Fusce sed sem pulvinar, vestibulum ex et, lacinia est. Vivamus consequat lacus ut velit tincidunt, ut consectetur nunc suscipit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam tempus nec mauris eu tincidunt. Ut bibendum ex efficitur ultrices pellentesque. Integer eget tempor enim. Praesent in tempus massa, eu tempus sem. Proin blandit a purus ut elementum.<br/><br/>Cras quis nisl et tortor consectetur tempor et vitae orci. Aliquam lobortis posuere nibh, id placerat nisl sollicitudin vitae. Etiam vulputate, leo id accumsan tristique, nunc est convallis risus, a tincidunt ex mi vel leo. Ut faucibus nulla ut ligula suscipit pulvinar. Donec in pretium turpis, vitae tincidunt lectus. Nam viverra velit purus, eget hendrerit nisl sagittis sit amet. In ultrices vel nulla nec dictum.<br/><br/>Donec varius condimentum ultrices. Nullam aliquet ultricies ex id tempus. Praesent viverra a libero consectetur volutpat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris at dolor ante. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce a quam eu ante maximus vestibulum sit amet vitae felis. Donec bibendum suscipit laoreet. Pellentesque ultricies bibendum est, ac cursus ex laoreet ut.<br/><br/>Integer vehicula tellus ac ipsum aliquam, vitae sollicitudin enim interdum. Nullam orci nisl, dapibus quis nisl non, scelerisque posuere tortor. Nunc ac molestie mi, ut commodo metus. Phasellus lobortis hendrerit nunc ac lobortis. Vestibulum nec tortor et lectus fringilla porttitor. Proin aliquam venenatis arcu. Mauris tempus massa iaculis nisl luctus efficitur. Sed facilisis suscipit tortor, sed cursus nulla semper at. Proin varius sollicitudin quam, sed interdum lacus egestas at. Vestibulum vehicula tristique orci, vitae consectetur erat varius a.<br/><br/>Vivamus fringilla neque ac luctus rhoncus. Curabitur turpis nisl, dignissim eu arcu at, facilisis hendrerit magna. Curabitur eget lectus massa. Phasellus ut ipsum sed dui ullamcorper tristique. Quisque et tortor nulla. Aliquam tincidunt tortor id urna mollis sodales. Nam sagittis tempor justo, quis cursus ipsum euismod vel. Pellentesque nec lacus nec sapien tincidunt consequat quis id leo. Nulla at rutrum ante.';
