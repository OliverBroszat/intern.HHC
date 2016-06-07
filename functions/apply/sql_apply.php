<?php
// Load WP-Functions
$root = realpath($_SERVER["DOCUMENT_ROOT"]);  
if (strpos($root, '\\')){  
  // localhost  
  $root .= "/wordpress";  
}  
require_once("$root/wp-load.php");



// kvtbl($_POST);

arr_to_tbl($_POST);

echo "<hr>";

var_dump($_FILES);

?>