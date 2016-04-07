<?php 

require_once('functions.php');
global $wpdb;

$select_sql ='Contact.id, Contact.first_name, Contact.last_name, Contact.birth_date, Ressort.name, Member.active, Member.position, Address.city, Address.postal, Phone.number, Study.school, Study.course';
$search_range = array(
	'Contact' => array(
		'first_name',
		'last_name',
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
$sort = '';

$search_text=$_GET["q"];

// SQL-Abfrage
$result = member_search($select_sql, $search_range, $search_text, $filter, $sort);

// Transform result to final output
$output = array();
foreach ($result as $key) {
	foreach ($key as $value) {
		array_push($output, trim($value));
	}
}
$suggest_list = array_unique(preg_grep("/.*$search_text.*/i", $output));
$max_entries = 4;
$suggest = array_reverse(array_slice($suggest_list,0,$max_entries));

foreach ($suggest as $value) {
	echo "<div class = 'suggest' onclick='add_to_search_box(this.innerHTML)'>$value</div>";
}


?>