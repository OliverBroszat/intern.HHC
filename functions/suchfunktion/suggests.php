<?php
/* 
	suggest.php 

	Suchvorschläge beim eintippen der Suchworte
*/



require_once('../main_functions.php');
global $wpdb;

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

// SQL-Abfrage
$result = member_search($search_select, $search_range, $search_text, $filter, $search_order);

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
