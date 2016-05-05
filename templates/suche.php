<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if (strpos($root, '\\')){
	// localhost
	$root .= "/wordpress";
}

require_once("$root/wp-content/themes/intern-hhc/functions/main_functions.php");
require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/AcceptPost.php");
require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/prepareSQL.php");
require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/getData.php");
require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/postProcess.php");
require_once("$root/wp-content/themes/intern-hhc/functions/suchfunktion/createHTML.php");


/* 
----------------------------------------
---------- Suchfunktionen ---------- 
----------------------------------------
*/

// POST übertragen
$input = AcceptPost($_POST, $_GET);
// SQL-Abfrage vorbereiten
$queries = prepareSQL($input);
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

// echo html_header('Suchfunktion');
?>

<div class = "outer">
	<h1>Suche</h1>

<!-- Suchfeld + Suchbutton -->
	<div class="panel">
		<form method="GET" id="form-suche">
			<table class="form">
				<tr>
					<td class="search-box-cell">
						<!-- onkeyup="suggest(this.value)" -->
						<input 
							id='text-box' 
							type="text" 
							name="search_text" 
							onkeyup="suggest(this.value)"
							value="<?php echo htmlspecialchars($_GET['search_text']);?>"
							placeholder="Suche..."
						>
						<div id="suggests"></div> 
					</td>
					<td>
						<button id="start-search" class='search'>Suchen</button>
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
		array('value' => 'Contact.id', 'name' => 'ID'),
		array('value' => 'Contact.first_name', 'name' => 'Vorname'),
		array('value' => 'Contact.last_name', 'name' => 'Nachname'), 
		array('value' => 'Contact.birth_date', 'name' => 'Alter'), 
		array('value' => 'Ressort.name', 'name' => 'Ressort'),
		array('value' => 'Member.active', 'name' => 'Status')
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
					name='f_ressort_list[]' 
					value='".$ressort[$i]->name."'
					".check('Ressort.name', $ressort[$i]->name).">
						".uppercase($ressort[$i]->name)."
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
							<label><input type='checkbox' name='f_position_list[]' value='anwärter' <?php echo check('Member.position', 'anwärter'); ?>> Anwärter</label><br>
							<label><input type='checkbox' name='f_position_list[]' value='mitglied' <?php echo check('Member.position', 'mitglied'); ?>> Mitglied</label><br>
						</td>
						<td>
							<label><input type='checkbox' name='f_position_list[]' value='ressortleiter' <?php echo check('Member.position', 'ressortleiter'); ?>> Ressortleiter</label><br>
							<label><input type='checkbox' name='f_position_list[]' value='alumni' <?php echo check('Member.position', 'alumni'); ?>> Alumni</label><br>
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
							<label><input type='checkbox' name='f_status_list[]' value='0' <?php echo check('Member.active', '0'); ?>> Aktiv</label><br>
						</td>
						<td>
							<label><input type='checkbox' name='f_status_list[]' value='1' <?php echo check('Member.active', '1'); ?>> Inaktiv</label><br>
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


				<button type="button" onclick="ajax_post();" class="full-width">Anwenden</button>
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


<script type = "text/javascript">

function ajax_post() {

	// http://stackoverflow.com/questions/9713058/sending-post-data-with-a-xmlhttprequest
	// Gute Infoquelle für ein POST Beispiel mit Ajax
	// Siehe vor allem die zweite Antwort mit FormData

	var hr = new XMLHttpRequest();
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			setTimeout(function(){
				// document.getElementsByTagName('body')[0].classList.remove('modal');
				document.getElementById('list-container').classList.remove('modal');
			    alert(hr.responseText);
			}, 800);
		}
	};
	hr.open("POST", "/wordpress/wp-content/themes/intern-hhc/functions/suchfunktion/AcceptAjax.php", true);
	// var b = document.getElementsByTagName('body')[0];
	var b = document.getElementById('list-container');
	b.className += " modal";
	hr.send();
}

</script>
 

<?php 
	// var_dump($filter);	
	echo html_footer();

?>

