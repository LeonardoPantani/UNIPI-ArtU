<?php
require_once("config/config.php");

header("Content-Type: text/plain; charset=utf-8");
readfile("./" . $folder_media . "/privacypolicy.txt");
