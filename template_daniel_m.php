<?php
/**
 * Template Name: Template Daniel Mentock
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
			echo '<h1>Hallo, Daniel!</h1><br>heh<br>wat<br>';
	
			$potato = $wpdb->get_results('SELECT * FROM TEST_USERS;');
			foreach($potato as $row){
var_dump($row);
			echo '<br><br>';
			}
?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
