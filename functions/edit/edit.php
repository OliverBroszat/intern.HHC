<?php
/*
 * Template Name: Edit
 */

// Load WP-Functions
$root = realpath($_SERVER["DOCUMENT_ROOT"]);  
if (strpos($root, '\\')){  
  // localhost  
  $root .= "/wordpress";  
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

