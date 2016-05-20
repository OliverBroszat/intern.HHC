<?php

// Load WP-Functions
$root = realpath($_SERVER["DOCUMENT_ROOT"]);  
if (strpos($root, '\\')){ $root .= "/wordpress"; }  
require_once("$root/wp-load.php");

$root = get_template_directory();
require_once("$root/functions/main_functions.php");
require_once("$root/functions/suchfunktion/prepareSQL.php");
require_once("$root/functions/suchfunktion/getData.php");
require_once("$root/functions/suchfunktion/postProcess.php");
require_once("$root/functions/suchfunktion/createHTML.php");


// Filter
$filter = array(
	"Ressort.name" => explode(',', $_POST['ressort_list']),
	"Member.position" => explode(',', $_POST['position_list']),
	"Member.active" => explode(',', $_POST['status_list']),
	"Study.school" => explode(',', $_POST['uni_list'])
);


// Filter
$input = array(
		'search' => $_POST['search_text'],
		'filter' => $filter,
		'sort' => $_POST['sort'],
		'order' => $_POST['order'],
		'ajax_call' => true,
	);


// Für den SELECT Operator
$search_select = array(
	'Contact' => array(
		'id',
		'prefix',
		'first_name',
		'last_name',
		'birth_date',
		'comment'
	),
	'Ressort' => array(
		'name'
	),
	'Member' => array(
		'active',
		'position',
		'joined',
		'left'
	)
);

// Für den LIKE Operator
$search_range = array(
	'Contact' => array(
		'id',
		'first_name',
		'last_name'
	)
);

	// Für den SELECT Operator
	$search_select = array(
		'Contact' => array(
			'id',
			'prefix',
			'first_name',
			'last_name',
			'birth_date',
			'comment'
		),
		'Ressort' => array(
			'name'
		),
		'Member' => array(
			'active',
			'position',
			'joined',
			'left'
		)
	);

	// Für den LIKE Operator
	$search_range = array(
		'Contact' => array(
			'id',
			'first_name',
			'last_name',
		)
	);

// --------- Suchfunktionen ---------

// SQL-Abfrage vorbereiten
$queries = prepareSQL($input, $search_select, $search_range);
// Datenbankabfrage
$data = getData($queries);
// Post-Processing
$final = postProcess($data);
// HTML-Tabelle
$html = createHTML($final);

<<<<<<< HEAD
	// SQL-Abfrage vorbereiten
	$queries = prepareSQL($input, $search_select, $search_range);
	// Datenbankabfrage
	$data = getData($queries);
	// Post-Processing
	$final = postProcess($data);
	// HTML-Tabelle
	$html = createHTML($final);
=======
echo $html;
>>>>>>> master

?>
