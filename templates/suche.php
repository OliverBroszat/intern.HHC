<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

?>

<div class = "outer clearfix">
	<h1>Suche</h1>

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
						<button type="button" id="start-search" class='search' onclick="ajax_post();">Suchen</button>
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
		echo "
			<option value='".$value[value]."'>
				".$value[name]."
			</option>
		";
	}
?>

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


<!-- AJAX Search -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search.js"></script>

<!-- Call AJAX Search on page load -->
<script type="text/javascript">window.onload=ajax_post;</script>

<!-- AJAX Search Suggestions -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search_suggestions.js"></script>


<?php get_footer(); ?>