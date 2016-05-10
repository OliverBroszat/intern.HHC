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

// Server:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/twentyfourteen-child/functions/main_functions.php");

echo html_header('Fehler');

?>
<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		ERROR <br>
		Es ist ein Fehler aufgetreten. Versuch es bitte nochmal oder benachrichtige das IT-Ressort.
	</div>
</div>

<?php
echo html_footer();
?>