<?php
/* 
	suggest.php 

	Suchvorschläge beim eintippen der Suchworte
*/



require_once('../main_functions.php');
// Server:
// $root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
$root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";

require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/getData.php");

$search_select = 
$search_range = array(
	'Contact' => array(
		'first_name',
		'last_name'
	),
	'Ressort' => array(
		'name'
	),
	'Address' => array(
		'city',
		'postal'
	),
	'Phone' => array(
		'number'
	),
	'Study' => array(
		'school',
		'course'
	)
);
$filter = array();
$search_order = '';

$search_text=$_GET["q"];

// Contact_ID =>	array(
// 		"info" => Ergebnis der "contact_search" Query, FÜR DIESEN CONTACT,
// 		"mails" => Ergebnisse der "mail" Query FÜR DIESEN CONTACT,
// 		"phones" => ...
// 		"addresses" => ...,
// 		"studies" => ...
// 		"image" => HMTML Code des Profilbilds (wird schon durch die Funktion getImageHTML bereitgestellt)
// 	)




// SQL-Abfrage
$result = member_search($search_select, $search_range, $search_text, $filter, $search_order);

var_dump($result);

// Transform result to final output
$output = array();
foreach ($result as $key) {
	foreach ($key['info'] as $value) {
		array_push($output, trim($value));
	}
}
//Suche nach Suchwörtern in output
$suggest = preg_grep("/.*$search_text.*/i", $output);
// Lösche Dublikate
$suggest = array_unique($suggest);

$suggest = array_slice($suggest,0,4);
// Liste umkehren, damit die besten Einträge ganz unten, also direkt über dem Suchfeld sind
$suggest = array_reverse($suggest);

foreach ($suggest as $value) {
	echo "<div class = 'suggest' onclick='add_to_search_box(this.innerHTML)'>$value</div>";
}

// var_dump($result);

?>
