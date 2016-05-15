<?php
/*
 * The template for displaying 404 pages (Not Found)
 */

get_header(); 

?>

	<div class="outer">
		<h1>404</h1>
		<div class="panel">
			<p>
				<b>Error 404</b>: Site not found.<br>

				<a href='<?php echo home_url(); ?>'>Back to home</a>.
			</p>
		</div>
	</div>

<?php get_footer(); ?>