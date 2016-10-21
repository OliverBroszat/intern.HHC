<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");


// Get MemberProfiles
$ids = $_GET['id'];
$memberController = new MemberDataController(null, new ContactDataController(null, new BaseDataController));
$memberProfiles = $memberController->getMultipleMemberProfilesByContactID($ids);


$txt = '';
// KEYS
foreach ($memberProfiles[0]->contactProfile->contactDatabaseRow->toArray() as $key => $value) {
	if($key != 'comment') {
		$txt .= "contact-$key;";
	}	
}
foreach ($memberProfiles[0]->memberDatabaseRow->toArray() as $key => $value) {
	$txt .= "member-$key;";
}
foreach ($memberProfiles[0]->contactProfile->addressDatabaseRows[0]->toArray() as $key => $value) {
	$txt .= "address-$key;";
}
foreach ($memberProfiles[0]->contactProfile->phoneDatabaseRows[0]->toArray() as $key => $value) {
	$txt .= "phone-$key;";
}
foreach ($memberProfiles[0]->contactProfile->mailDatabaseRows[0]->toArray() as $key => $value) {
	$txt .= "mail-$key;";
}
foreach ($memberProfiles[0]->contactProfile->studyDatabaseRows[0]->toArray() as $key => $value) {
	$txt .= "study-$key;";
}
$txt .= "\n";

// VALUES
foreach ($memberProfiles as $memberProfile) {
	foreach ($memberProfile->contactProfile->contactDatabaseRow->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	foreach ($memberProfile->memberDatabaseRow->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	foreach ($memberProfile->contactProfile->addressDatabaseRows[0]->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	foreach ($memberProfile->contactProfile->phoneDatabaseRows[0]->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	foreach ($memberProfile->contactProfile->mailDatabaseRows[0]->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	foreach ($memberProfile->contactProfile->studyDatabaseRows[0]->toArray() as $key => $value) {
		$temp = trim(preg_replace('/\s+/', ' ', $value)).';';
		if(!empty($temp)){ $txt .= $temp; }
		else { $txt .= ';'; }
	}
	$txt .= "\n";
}


/* ------ FILE ------ */
date_default_timezone_set('Europe/Berlin'); 
// output headers so that the file is downloaded rather than displayed 
header('Content-Type: text/csv; charset=utf-8'); 
header('Content-Disposition: attachment; filename=mitgliederliste_'.date('Y-m-d_H-m-s').'.csv'); 
 
// create a file pointer connected to the output stream 
$file = fopen('php://output', 'w'); 

// write data to file
fwrite($file, $txt);
fclose($file);