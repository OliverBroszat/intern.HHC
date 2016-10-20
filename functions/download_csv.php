<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");


date_default_timezone_set('Europe/Berlin');
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mitgliederliste_'.date('Y-m-d_H-m-s').'.csv');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 

// create a file pointer connected to the output stream
// $output = fopen('php://output', 'w');

//fetch the data
// $list = array (
// 	array("Vornam","Nachname","Stadt","Land"),
// 	array("Peter","Griffin","Oslo","Norway"),
// 	array("Glenn","Quagmire","Oslo","Norway"),
// );



// loop over the rows, outputting them
// foreach ($list as $line){
//   	fputcsv($output, $line, ";");
// }


// create searchController with $_POST-Data
$searchController = new searchController($_POST);
// Get MemberProfiles
$memberProfiles = $searchController->search();


$txt = '';
// KEYS
foreach ($memberProfiles[0]->memberDatabaseRow->toArray() as $key => $value) {
	$txt .= "member-$key;";
}
foreach ($memberProfiles[0]->contactProfile->contactDatabaseRow->toArray() as $key => $value) {
	if($key != 'comment') {
		$txt .= "contact-$key;";
	}	
}
foreach ($memberProfiles[0]->contactProfile->studyDatabaseRows[0]->toArray() as $key => $value) {
	$txt .= "study-$key;";
}
$txt .= "\n";

// VALUES
foreach ($memberProfiles as $memberProfile) {
	foreach ($memberProfile->memberDatabaseRow->toArray() as $key => $value) {
		$txt .= "$value;";
	}
	foreach ($memberProfile->contactProfile->contactDatabaseRow->toArray() as $key => $value) {
		if($key != 'comment') {
			$txt .= "$value;";
		}
	}
	foreach ($memberProfile->contactProfile->studyDatabaseRows[0]->toArray() as $key => $value) {
		$txt .= "$value;";
	}
	$txt .= "\n";

	// foreach ($memberProfile->contactProfile as $table => $rows) {
	// 	foreach ($rows as $index => $address) {
	// 		foreach ($address->	toArray() as $key => $value) {
	// 			echo "$table-$index-$key: $value; ";
	// 		}
	// 		echo "<br>";	
	// 	}
	// 	echo "<br>";	
	// }
}

$file_name = 'mitgliederliste_'.date('Y-m-d_H-m-s').'.csv';
$myfile = fopen($file_name, "w") or die("Unable to open file!");
fwrite($myfile, $txt);
fclose($myfile);

print $file_name;
