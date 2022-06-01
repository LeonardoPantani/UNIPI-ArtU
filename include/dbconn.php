<?php
$dbconn = @new mysqli($db_host, $db_username, $db_password, $db_dbname);

if ($dbconn->connect_error) {
	echo "error_dbconn";
} else {
	$dbconn->set_charset('utf8mb4');
}
