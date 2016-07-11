<?php
/**
 * Template Name: Suche
 */

get_header();

$root = get_template_directory();

require_once("$root/functions/main_functions.php");
require_once("$root/functions/suchfunktion/AcceptPost.php");
require_once("$root/functions/suchfunktion/prepareSQL.php");
require_once("$root/functions/suchfunktion/getData.php");
require_once("$root/functions/suchfunktion/postProcess.php");
require_once("$root/functions/suchfunktion/createHTML.php");


/* 
----------------------------------------
---------- Suchfunktionen ---------- 
----------------------------------------
*/

// Spalten, die ausgewählt werden
$search_select = array(
	'Contact' => array(
		'id',
		'prefix',
		'first_name',
		'last_name',
		'birth_date',
		'comment'
	),
	'Ressort' => array(
		'name'
	),
	'Member' => array(
		'active',
		'position',
		'joined',
		'left'
	)
);


// Spalten, nach denen gesucht werden kann
$search_range = array(
	'Contact' => array(
		'first_name',
		'last_name'
	),
	'Ressort' => array(
		'name'
	),
	'Address' => array(
		'city',
		'postal'
	),
	'Phone' => array(
		'number'
	),
	'Study' => array(
		'school',
		'course'
	)
);


// POST übertragen
$input = AcceptPost($_POST, $_GET);
// SQL-Abfrage vorbereiten
$queries = prepareSQL($input, $search_select, $search_range);
// Datenbankabfrage
$data = getData($queries);
// Post-Processing
$final = postProcess($data);
// HTML-Tabelle
$html = createHTML($final);


/* 
----------------------------------------
---------- HTML-Seite ---------- 
----------------------------------------
*/

?>


<div class = "outer clearfix">
	<h1>Mitgliederliste</h1>

<!-- Suchfeld + Suchbutton -->
	<div class="panel">
		<form method="POST" id="form-suche">
			<table class="form">
				<tr>
					<td class="search-box-cell">
						<input 
							id='text-box' 
							type="text" 
							name="search_text" 
							class="searchinput"
							onkeyup="ajax_search_suggestions(this.value)"
							placeholder="Suche..."
						>
						<div id="suggestions"></div> 
					</td>
					<td>
						<button type='submit' id='start-search' class='search' >Suchen</button>
					</td>
				</tr>
			</table>
		</form>
	</div><!-- /panel -->


	<button id="sidebar-toggle" class="search" onclick="$(this).toggleClass('show'); $('.sidebar').slideToggle(300);">Suchoptionen</button>

	<div class = "sidebar">
		
	<!-- Sortieren -->
		<div class='panel'>
			<form method='POST' id='form-sortieren'>
				<h2>Sortieren nach:</h2>
				<table >
					<tr>
						<td>
						<select name="sort" id="sort" onchange="ajax_post()">

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
						</td>
						<td>
							<select name="order" id="order" onchange="ajax_post()" style='width:'>
								<option value="asc">A-Z</option>
								<option value="desc">Z-A</option>
							</select>
						</td>
					</tr>
				</table>
			</form>
		</div><!-- /panel -->
	

	
	<!-- Filter -->
		<div class = "panel filter">
			<form method="POST">
				<h2>Filtern nach:</h2>


<?php

// Filter-Data
	$ressorts = res_to_array($wpdb->get_results("SELECT name FROM Ressort"));
	$positions = res_to_array($wpdb->get_results("SELECT position FROM Member"));
	$status = array('0', '1');
	$schools = res_to_array($wpdb->get_results("SELECT school FROM Study"));

	$filter = array(
		array('name' => 'ressort', 'title' => 'Ressort', 'data' => $ressorts, 'cols' => 2),
		array('name' => 'position', 'title' => 'HHC Position', 'data' => $positions, 'cols' => 2),
		array('name' => 'status', 'title' => 'HHC Status', 'data' => $status, 'cols' => 2),
		array('name' => 'uni', 'title' => 'Universität', 'data' => $schools, 'cols' => 1)
	);


	foreach ($filter as $category) {
		$cols = $category['cols'];
		$data = $category['data'];
		$name = $category['name'];

		echo("
			<table>
				<tr>
					<th colspan='$cols'>
						".$category['title']."
					</th>
				</tr>
				
					
		");

		for ($i=0; $i < $cols; $i++) { 		
			$count = count($data)/$cols;
			// Problem: doppelte Einträge, wenn $count einen Rest hat

			$width = 100/$cols;
			echo "<td style='width:".$width."%'><table>";
			
			for ($j=0; $j < $count; $j++) { 					
				$value = $data[$j + $i * $count];
				echo "
					<tr>
						<td width='1px'>
							<input 
								type='checkbox'
								name='f_".$name."_list[]'
								value='$value' 
								id='f_".$name."_".$value."'
								class='filtercheckbox_".$name."'
							>
						</td>
						<td>
							<label for='f_".$name."_".$value."'>&nbsp;".uppercase(bool_to_lbl($value))."</label>
						</td>
					</tr>
				";
			}
			echo "</td></table>";
		}
		echo "</tr></table>";
	}

?>

			
				<input type="hidden" name="templateDirectory" id="templateDirectory" value="<?php echo get_template_directory_uri(); ?>">

				<button type="button" onclick="ajax_post();" class="full-width">Aktualisieren</button>
			</form>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	

	<main class="container">
		<form method='POST'>
			<div class="panel actions">					

				<button type='button' id='new-entry' value='new' onclick='edit(this.value);'>Neu</button>

				<button type='button' onclick='edit_multi()'>Edit selected</button>

				<button type='button' onclick='select_all()'>Select/Deselect all</button>

			</div><!-- /panel -->


	<!--  Suchergebnisse -->
			<div class='panel'>
				
					<h2 id='search-results-title'>Suchergebnisse (0)</h2>
					<div id='list-container'>
						<!--<div class="modal"> Place at bottom of page </div>-->
						<?php echo $html ?>
					</div>
				
			</div><!-- /panel -->
		</form>
	</main>
	
</div><!-- /outer -->


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
<script src="<?php echo get_template_directory_uri(); ?>/js/edit_multi.js"></script> 

<!-- Expand Detail Content -->
<script>
	function expand_content(value){
		$('#slide_content_show_detail_'+value).slideToggle(300);
	}
</script>



<?php get_footer(); ?>