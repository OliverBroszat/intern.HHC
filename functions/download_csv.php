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

// CSV Controller
$csv = new CSV();
$csvArray = $csv->generateCsvForMemberProfiles($memberProfiles);
$csvString = $csv->printCSV();

// debug output:
// arr_to_list($csvArray);
// exit();

// File Download
date_default_timezone_set('Europe/Berlin'); 
header('Content-Disposition: attachment; filename=mitgliederliste_'.date('Y-m-d_H-m-s').'.csv'); 
header('Content-Type: text/csv; charset=utf-8'); 

// create a file pointer connected to the output stream 
$file = fopen('php://output', 'w'); 

// write data to file
fwrite($file, convertToWindowsCharset($csvString));
fclose($file);