<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");


// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mitgliederliste.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

//fetch the data
$list = array (
	array("Vornam","Nachname","Stadt","Land"),
	array("Peter","Griffin","Oslo","Norway"),
	array("Glenn","Quagmire","Oslo","Norway"),
);

// loop over the rows, outputting them
foreach ($list as $line){
  	fputcsv($output, $line, ";");
}


?>