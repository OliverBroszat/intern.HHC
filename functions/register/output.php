<?php

// Load WP-Functions
$root = realpath($_SERVER["DOCUMENT_ROOT"]);  
if (strpos($root, '\\')){  
  // localhost  
  $root .= "/wordpress";  
}  
require_once("$root/wp-load.php");

get_header();

//	Meldung ob die Übertragung der Daten zu der Datenbank funktioniert hat

$status = $_GET['status'];

if ($status == 'ok'){
	echo "<div class='msg ok'>Vielen Dank!<br>Deine Daten wurden übernommen!</div>";
}
else{
	echo "<div class='msg'>Leider Hat die Dateneingabe nicht geklappt. Hast du alle Pflichtfelder ausgefüllt?<br>Bitte gehe zurück und versuche es noch einmal</div>";
}
?>