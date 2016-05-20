<?php

//	Fehlermeldung für die Suchseite

// Load WP-Functions
$root = realpath($_SERVER["DOCUMENT_ROOT"]);  
if (strpos($root, '\\')){  
  // localhost  
  $root .= "/wordpress";  
}  
require_once("$root/wp-load.php");


get_header();

?>
<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		ERROR <br>
		Es ist ein Fehler aufgetreten. Versuch es bitte nochmal oder benachrichtige das IT-Ressort.
	</div>
</div>

<?php get_footer(); ?>