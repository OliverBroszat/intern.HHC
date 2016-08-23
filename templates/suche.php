<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

//Falls der Besucher nicht eingeloggt ist, wird er auf die Startseite zurückgeleitet
// if(!is_user_logged_in() )
// {
// 	wp_redirect( home_url( '' ) );
// 	exit();
// }

?>

<div class = "outer clearfix">
	<h1>Mitgliederliste</h1>
	
<!-- Suchfeld + Suchbutton -->
<form method="POST" id="form-suche" action="<?php echo get_template_directory_uri(); ?>/functions/search/sql_search.php">
	<div id="search-box">
		<div class="ui segment search-box-cell">
			<div class="search-box-cell">
				<div class="fluid ui right action left icon input">
					<i class="search icon"></i>
				  	<input type="text" name="search_text" id='text-box' onkeyup="ajax_search_suggestions(this.value)" placeholder="Suchen" >
				  	<button class="ui primary icon button"  style="padding-left: 2rem; padding-right: 2rem;" id='start-search'>
				  		<i class="search icon"></i>
				  		<!-- Suchen -->
				  	</button>
				</div>
				<div id="suggestions"></div>
				<script>$("#suggestions").css("width", $("#text-box").css("width"));</script>
			</div>
		</div><!-- /panel -->

	</div>



	<button id="sidebar-toggle" class="search" onclick="$(this).toggleClass('show'); $('.sidebar').slideToggle(300);">Suchoptionen</button>

	<div class = "sidebar">
		
	<!-- Sortieren -->
		<div class='ui segment'>
			<h2>Sortieren nach:</h2>
			<div class="ui form">
				  <div class="fields">
				    <div class="ten wide field">
						<select class="ui fluid dropdown" name="sort" id="sort" onchange="ajax_post()">
							<!-- <option value="">Sort</option> -->

<?php
	// Sortieren
	$t_header = array(
		array('value' => 'Contact.last_name', 'name' => 'Nachname'), 
		array('value' => 'Contact.first_name', 'name' => 'Vorname'),
		array('value' => 'Contact.birth_date', 'name' => 'Alter'), 
		array('value' => 'Ressort.name', 'name' => 'Ressort'),
		array('value' => 'Member.active', 'name' => 'Status'),
		array('value' => 'Contact.id', 'name' => 'ID')
	);	


	// Print Sortieren
	foreach ($t_header as $value) {	
		echo "<option value='".$value[value]."'>".$value[name]."</option>";
	}
?>

						</select>
					</div>
					<div class="six wide field"				>
						<select class="ui fluid dropdown" name="order" id="order" onchange="ajax_post()">
							<!-- <option value="">Order</option> -->
							<option value="asc">A-Z</option>
							<option value="desc">Z-A</option>
						</select>
					</div>
				</div>
			</div>

		</div><!-- /panel -->
	

	
	<!-- Filter -->
		<div class = "ui segment filter">
				<h2>Filtern nach:</h2>
<?php 

$filters = new FilterLists();
foreach ($filters->getSearchFilter() as $filter) {
	$filter->createHtmlTable();
}



?>

			
				<input type="hidden" disabled name="templateDirectory" id="templateDirectory" value="<?php echo get_template_directory_uri(); ?>">

				<button type='button' class='fluid ui labeled icon button' onclick="ajax_post();">
					<i class="refresh icon"></i>
					Aktualisieren
				</button>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	</form>

	<main class="container">
		<div class="ui segment actions">					

			<button type='button' class='ui labeled icon button' id='new-entry' value='new' onclick="edit('');">
				<i class="file outline icon"></i>
				Neu
			</button>

			<button type='button' class='ui labeled icon button' onclick='edit_multi()'>
				<i class="edit icon"></i>
				Edit selected
			</button>

			<button type='button' class='ui labeled icon button' onclick='select_all()'>
				<i class="checkmark box icon"></i>
				Select/Deselect all
			</button>

		</div><!-- /panel -->

<!--  Suchergebnisse -->
		<div class='ui segment'>
			
				<h2 id='search-results-title'>Suchergebnisse (0)</h2>
				<div id='list-container'>
					<!--<div class="modal"> Place at bottom of page </div>-->
					<?php echo $html ?>
				</div>
			
		</div><!-- /panel -->
	</main>
	
</div><!-- /outer -->


<!-- Semantic UI -->
<script>
	$('.ui.checkbox').checkbox();
	$('.ui.dropdown').dropdown();
</script>

<!-- Import ajax_post() function -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search.js"></script>

<!-- Call AJAX Search on page load -->
<script>window.onload=ajax_post;</script>

<!-- Call AJAX Search on #form-suche submit -->
<script>
	$("#form-suche").submit(function(e){
	    e.preventDefault();
	    $("#start-search").focus();
	    ajax_post();

	});
</script>

<!-- AJAX Search Suggestions -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search_suggestions.js"></script>

 <!-- AJAX Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_edit.js"></script> 

<!-- Multi-Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_edit_multi.js"></script> 

<!-- Expand Detail Content -->
<script>
	function expand_content(value){
		$('#slide_content_show_detail_'+value).slideToggle(300);
		$('#slide-content-button-'+value+' i').toggleClass('down');
	}
</script>



<?php get_footer(); ?>