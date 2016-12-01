<?php
/**
 * Template Name: Error
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


get_header();

?>

<div class="outer">
	<h1>ERROR</h1>
	<div class="panel">
		<p>Es ist ein Fehler aufgetreten. Versuch es bitte nochmal oder benachrichtige das IT-Ressort.</p>
	</div>
	<div class="panel">
		<h2>Fehlermeldung</h2>
		<p>
		<?php
			$wpdb->show_errors();
			$wpdb->print_error();
			$wpdb->last_error;
		?>
		</p>	
	</div>
</div>

