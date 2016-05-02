<?php
/**
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// Server:
// $root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
$root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/intern-hhc/functions/main_functions.php");

get_header();

echo html_footer();
?>
<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		Das ist die intern.HHC Startseite
	</div>
</div>

