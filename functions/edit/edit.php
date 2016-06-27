<?php

/*
	Diese Datei wird über AJAX (/js/ajax_edit.js) aufgerufen, fragt zu einer ID die Daten ab und gibt diese zurück.
*/

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

$root = get_template_directory();
require_once("$root/functions/suchfunktion/prepareSQL.php");
require_once("$root/functions/suchfunktion/getData.php");
require_once("$root/functions/edit/createHTML.php");


$id = $_POST['id'];

// SQL-Abfrage vorbereiten
$queries = prepareSQL($id);
// Datenbankabfrage
$data = getData($queries)[$id];
// HTML-Tabelle
$html = createHTML($data);


echo $html;

?>

