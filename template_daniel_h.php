<?php
/**
 * Template Name: Template Daniel Högel
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
			
			echo "<style type='text/css'>
				.table{
					display:table;
					width: 100%;
					max-width: 240px;
					margin:50px auto;
					 border-collapse:collapse; 
					
				}
				.table-row{
					display:table-row;
				}
				.table-cell{
					display:table-cell;
					border: 1px solid #555;
					padding: 10px;
					overflow:hidden;
					max-height: 3rem;
				}
				.table-row.table-head{
					background-color: #ccc;
					font-weight: bold;
				}
			</style>";
			
			// HIER GEHT'S LOS
			echo '<h1>Hallo, Daniel! und auf Wiedersehen</h1>';
			

			$arr = $wpdb->get_results('SELECT * FROM TEST_USERS;');
			
			echo "<div class='table'>";
			echo "
			<div class='table-row table-head'>
				<div class='table-cell'>ID</div>
				<div class='table-cell'>Vorname</div>
				<div class='table-cell'>Nachname</div>
				<div class='table-cell'>E-Mail</div>
				<div class='table-cell'>Fach</div>
				<div class='table-cell'>Anmerkung</div>
			</div>";
			foreach($arr as $row){
				echo "<div class='table-row'>";
				foreach($row as $col){
					echo "<div class='table-cell'>";
						echo $col;
					echo "</div>";
				}
				echo "</div>";
			}
			echo "</div>";


			
			
			
			
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();