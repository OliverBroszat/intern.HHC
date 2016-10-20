<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

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

date_default_timezone_set('Europe/Berlin');
$file_name = 'mitgliederliste_'.date('Y-m-d_H-m-s').'.csv';

$handle = fopen($file_name, "w") or die("Unable to open file!");
fwrite($handle, $txt);
fclose($handle);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=$file_name');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60))); // 60 Sek
  
print $file_name;