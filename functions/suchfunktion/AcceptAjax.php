<?php
	
	// var_dump($_POST);

	$filter = array(
		"Ressort.name" => explode(',', $_POST['ressort_list']),

		"Member.position" => explode(',', $_POST['position_list']),
		"Member.active" => explode(',', $_POST['status_list']),
		"Study.school" => explode(',', $_POST['uni_list'])
	);

	$input = array(
		'search' => $_POST['search_text'],
		'filter' => $filter,
		'sort' => $_POST['sort'],
		'order' => 'ASC',
		'ajax_call' => true,
	);


	// Load WP-Functions
	$root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
	if (strpos($root, '\\')){  
	 // localhost  
	 $root .= "/wordpress";  
	}  
	$whitelist = array(
	    '127.0.0.1',
	    '::1'
	);
	if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
	    $root = $root."/wordpress";
	}
	require_once("$root/wp-load.php");
	$root = get_template_directory();

	require_once("$root/functions/main_functions.php");
	require_once("$root/functions/suchfunktion/prepareSQL.php");
	require_once("$root/functions/suchfunktion/getData.php");
	require_once("$root/functions/suchfunktion/postProcess.php");
	require_once("$root/functions/suchfunktion/createHTML.php");





	// SQL-Abfrage vorbereiten
	$queries = prepareSQL($input);
	// Datenbankabfrage
	$data = getData($queries);
	// Post-Processing
	$final = postProcess($data);
	// HTML-Tabelle
	$html = createHTML($final);

	echo $html;
?>