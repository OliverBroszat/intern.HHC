<?php
/**
 * Template Name: Volle Breite
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

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
			// - - - - - INSERT PHP CODE HERE!
			// - - - - - END
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
