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
					<td>
							<button type='button' id='new-entry' class='search' 	value='new' onclick='edit(this.value);'>NEU</button>
					</td>
				</tr>
			</table>
		</form>
	</div><!-- /panel -->



	<div class = "sidebar">
		
	<!-- Sortieren -->
		<div class='panel'>
			<form method='POST'>
				<h2>Sortieren nach:</h2>

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

				<select name="order" id="order" onchange="ajax_post()" style='width:'>
					<option value="asc">A-Z</option>
					<option value="desc">Z-A</option>
				</select>
			</form>
		</div><!-- /panel -->
	

	
	<!-- Filter -->
		<div class = "panel filter">
			<form method="POST">
				<h2>Filtern nach:</h2>
			<!-- Ressort -->
				<table>
					<tr>
						<th colspan="2">
							Ressort<br>
						</th>
					</tr>
					<tr>
						<td>

<?php 
	// Ressort Checkboxen
	$ressort = $wpdb->get_results("SELECT name FROM Ressort");
	for ($i = 0; $i < (sizeof($ressort)/2); $i++) {
		echo "
			<label>
				<input 
					type='checkbox'
					class='filtercheckbox_ressort'
					name='f_ressort_list[]' 
					value='".$ressort[$i]->name."'
					".check('Ressort.name', $ressort[$i]->name).">
						 ".uppercase($ressort[$i]->name)."
			</label><br>";
	}
	echo "</td><td>";
	for ($i; $i < (sizeof($ressort)); $i++) {
		echo "
			<label>
				<input 
					type='checkbox'
					class='filtercheckbox_ressort'
					name='f_ressort_list[]' 
					value='".$ressort[$i]->name."'
					".check('Ressort.name', $ressort[$i]->name).">
					".uppercase	($ressort[$i]->name)."	
				</label><br>";
	}
	?>	


						</td>
						</tr>
					</table>
	
			<!-- Position -->
				<table>
					<tr>
						<th colspan="2">
							HHC Position<br>
						</th>
					</tr>
					<tr>
						<td>
							<label><input type='checkbox' class='filtercheckbox_position' name='f_position_list[]' value='anwärter' <?php echo check('Member.position', 'anwärter'); ?>> Anwärter</label><br>
							<label><input type='checkbox' class='filtercheckbox_position' name='f_position_list[]' value='mitglied' <?php echo check('Member.position', 'mitglied'); ?>> Mitglied</label><br>
						</td>
						<td>
							<label><input type='checkbox' class='filtercheckbox_position' name='f_position_list[]' value='ressortleiter' <?php echo check('Member.position', 'ressortleiter'); ?>> Ressortleiter</label><br>
							<label><input type='checkbox' class='filtercheckbox_position' name='f_position_list[]' value='alumni' <?php echo check('Member.position', 'alumni'); ?>> Alumni</label><br>
						</td>
					</tr>
				</table>

			<!-- Status -->
				<table>
					<tr>
						<th colspan="2">
							HHC Status<br>
						</th>
					</tr>
					<tr>
						<td>
							<label><input type='checkbox' class='filtercheckbox_status' name='f_status_list[]' value='0' <?php echo check('Member.active', '0'); ?>> Aktiv</label><br>
						</td>
						<td>
							<label><input type='checkbox' class='filtercheckbox_status' name='f_status_list[]' value='1' <?php echo check('Member.active', '1'); ?>> Inaktiv</label><br>
						</td>
					</tr>
				</table>

			<!-- Uni -->
				<table>
					<tr>
						<th colspan="2">
							Universität<br>
						</th>
					</tr>
					
					<?php					
						$result = $wpdb->get_results("SELECT school FROM Study");
						
						$result_array = array();
						foreach ($result as $key) {
							array_push($result_array, $key->school);
						}

						$uni = array_unique($result_array);

						foreach ($uni as $value) {
							echo "
								<tr>
									<td width='10%'>
										<input
											type='checkbox'
											class='filtercheckbox_uni'
											name='f_uni_list'
											value='$value'
											".check('Study.school',$value).">
									</td>
									<td>
										$value
									</td>
								</tr>
							";
						}
					?>
						
				</table>
				
				<input type="hidden" name="templateDirectory" id="templateDirectory" value="<?php echo get_template_directory_uri(); ?>">

				<button type="button" onclick="ajax_post();" class="full-width">Aktualisieren</button>
			</form>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	

	<main class="container">

<!--  Suchergebnisse -->
		<div class='panel'>
			<form method='POST'>
				<h2>Suchergebnisse</h2>
				<div id='list-container'>
					<!--<div class="modal"> Place at bottom of page </div>-->
					<?php echo $html ?>
				</div>
			</form>
		</div><!-- /panel -->	
	</main>
	
</div><!-- /outer -->

<div id="popup-blende"></div>
<div id="popup-edit" class="panel">
	<h2>Eintrag bearbeiten</h2>
	<div id="popup-content"></div>
	<div id="popup-footer">
		<button> Speichern </button> 
		<button onclick="popup_close()"> Abbrechen </button> 
	</div>
</div>


<!-- AJAX Search -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search.js"></script>

<!-- Call AJAX Search on page load -->
<script type="text/javascript">window.onload=ajax_post;</script>

<!-- Call AJAX Search on #form-suche submit -->
<script type="text/javascript">
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


<?php get_footer(); ?>