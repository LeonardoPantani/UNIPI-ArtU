<?php

/**
 * File di configurazione. Contiene alcune variabili modificabili e altre che indicano i percorsi dei vari componenti del sito.
 */

$service_name = "ServiceName";
$service_motto = "Fai qualcosa di straordinario";
$service_version = "20220603";
$defaultavatar_file = "default.jpg";

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_dbname = "esame";

$table_users = "users";
$table_usercontent = "usercontent";
$table_friends = "friends";
$table_friendrequests = "friendrequests";

$folder_backend = "backend";
$folder_include = "include";
$folder_css = "css";
$folder_scripts = "scripts";
$folder_media = "media";

// massimi tag 30, lunghezza totale singolo tag 20, con aggiunto virgola e spazio 22, totale: 660 caratteri possibili (700 nel database per sicurezza)
$content_tag_maxlength = 20;
$content_tag_maxnumber = 30;

$content_note_maxlength = 1000;
$content_file_maxsize = 10000000; // 10 mb = 10 milioni di byte
$content_thumbnail_maxsize = 1000000; // 1mb = 1 milione di byte

$usercontent_types = ["photo", "video", "drawing", "music", "text", "poetry"];

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
