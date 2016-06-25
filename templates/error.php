<?php
/**
 * Template Name: Home
 * Author: Daniel
 * Status: 05.04.2016, 19:00 Uhr
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

<<<<<<< HEAD
// Serverwurzelverzeichnis setzen:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
=======
// Server:
$localhost = array(
    '127.0.0.1',
    '::1'
);

$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
    if (strpos($root, '\\')){ $root .= "/wordpress"; }
}
require_once("$root/wp-load.php");
>>>>>>> Oliver_Zeugnis

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/twentyfourteen-child/functions/main_functions.php");

echo html_header('Home');

echo html_footer();
?>
<!--Fehlerseite für 404-->
<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		ERROR <br>
		Es ist ein Fehler aufgetreten. Versuch es bitte nochmal oder benachrichtige das IT-Ressort.
	</div>
</div>

