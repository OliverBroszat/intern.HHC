<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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