<?php
/**
 * Template Name: Template Oliver Fritzsch
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

if (!is_user_logged_in()) { auth_redirect(); }
get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
			
			// HIER GEHT'S LOS
			echo '<h1>Hallo, Oliver!</h1><br> Hurra, es funktioniert :)';
			$array = $wpdb->get_results('SELECT * FROM TEST_USERS;');
			foreach($array as $row) {
				
			}
			

			$headline = '<td><b>Vorname</b></td> <td><b>Nachname</b></td> <td><b>Studium</b></td>';
			echo '<table>';
			echo '<tr>' . $headline . '</tr>';
			foreach( $array as $row ) {
				echo "<tr><td>$row->first_name</td> <td>$row->last_name</td> <td>$row->studium</td></tr>";
			}
			echo '</table>';
			echo '<br>';
			echo $_GET["wert"];
			echo '<h2>ich bin aus PHP gemacht </h2>';
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();