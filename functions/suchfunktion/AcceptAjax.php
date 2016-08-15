<?php

// Load WP-Functions
$localhost = array('127.0.0.1', '::1');
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
}
require_once("$root/wp-load.php");



// create searchController with $_POST-Data
$searchController = new searchController($_POST);

// serach for member profiles
$memberProfiles = $searchController->search();

$html = '';
foreach ($memberProfiles as $memberProfile) {
	$html .= "
		{$memberProfile->contactProfile->contactDatabaseRow->getValueForKey('first_name')} 
		{$memberProfile->contactProfile->contactDatabaseRow->getValueForKey('last_name')}
		(
			{$memberProfile->memberDatabaseRow->getValueForKey('active')}, 
			{$memberProfile->memberDatabaseRow->getValueForKey('position')}, 
			{$memberProfile->memberDatabaseRow->getValueForKey('ressort')}
		)
		
		[{$memberProfile->memberDatabaseRow->getValueForKey('contact')}]
	";
	$html .= '<br>';
}

// var_dump($html);

$number = sizeof($memberProfiles);

$return = array(
	'number' => $number,
	'html' => $html,
	'debug' => $memberProfiles
);

print json_encode($return);

return $html;



// $root = get_template_directory();
// require_once("$root/functions/suchfunktion/prepareSQL.php");
// require_once("$root/functions/suchfunktion/getData.php");
// require_once("$root/functions/suchfunktion/postProcess.php");
// require_once("$root/functions/suchfunktion/createHTML.php");

// // SQL-Abfrage vorbereiten
// $queries = prepareSQL($input, $search_select, $search_range);
// // Datenbankabfrage
// $data = getData($queries);
// // Post-Processing
// $final = postProcess($data);
// // HTML-Tabelle
// $html = createHTML($final);



?>
