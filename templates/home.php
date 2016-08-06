<?php
/**
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

?>

<div class="outer">

	<h1 style="text-transform: none;">intern.HHC</h1>

	<div class="panel">
		Das ist die intern.HHC Startseite
		<?php
		if (is_user_logged_in()) {
			echo 'ID: ' . wp_get_current_user()->ID;
		}
		?>
	</div>

</div>


<?php get_footer(); ?>