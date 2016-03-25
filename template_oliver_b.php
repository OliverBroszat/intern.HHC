<?php
/**
 * Template Name: Template Oliver Broszat
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
			
			$array = $wpdb->get_results('SELECT * FROM TEST_USERS;');

			echo "<table>
				<tr>
					<td>Vorname</td><td>Nachname</td><td><Studium></td>
				</tr>";
				
				foreach($array as $row){
					echo"<tr>
							<td>$row->first_name</td><td>$row->last_name</td><td>$row->studium</td>
						</tr>";
				}
				
			echo "</table>"
			?>
		ich mag php
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();