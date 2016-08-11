<?php
/**
 * Template Name: Error
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");


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

