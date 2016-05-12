<?php
/**
 * Template Name: Error
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

require_once(get_template_directory()."/functions/main_functions.php");

get_header();

?>
<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
		ERROR <br>
		Es ist ein Fehler aufgetreten. Versuch es bitte nochmal oder benachrichtige das IT-Ressort.
	</div>
</div>

<?php get_footer(); ?>