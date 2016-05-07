<?php
/**
 * Template Name: Suche
 * Author: Daniel
 * Status: 07.04.2016, 18:00 Uhr
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */


// Server:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/twentyfourteen-child/functions/main_functions.php");

require_once("$root/wp-content/themes/twentyfourteen-child/functions/suchfunktion/AcceptPost.php");
require_once("$root/wp-content/themes/twentyfourteen-child/functions/suchfunktion/prepareSQL.php");
require_once("$root/wp-content/themes/twentyfourteen-child/functions/suchfunktion/getData.php");
require_once("$root/wp-content/themes/twentyfourteen-child/functions/suchfunktion/postProcess.php");
require_once("$root/wp-content/themes/twentyfourteen-child/functions/suchfunktion/createHTML.php");


// require_once("$root/wp-content/themes/twentyfourteen-child/functions/member_search.php");


/* 
----------------------------------------
---------- Suchfunktionen ---------- 
----------------------------------------
*/

// echo "<br><hr><b>DEBUGGING:</b><br>";


// POST übertragen
$input = AcceptPost($_POST, $_GET);
// Debug Output
// echo "<br><br><b>Input:</b><br>";
// var_dump($input);


// SQL-Abfrage vorbereiten
$queries = prepareSQL($input);
// Debug Output
// echo "<br><br><b>SQL:</b><br>";
// var_dump($queries);


// Datenbankabfrage
$data = getData($queries);
// Debug Output
//echo "<br><br><b>Data:</b><br>";
//var_dump($data);


// Post-Processing
$final = postProcess($data);
// Debug Output
// echo "<br><br><b>Final:</b><br>";
// var_dump($final);


// HTML-Tabelle
$html = createHTML($final);
// Debug Output
// echo "<br><br><b>HTML-Tabelle:</b><br>";
// var_dump($html);


// echo "<hr><br>";


/* 
----------------------------------------
---------- HTML-Seite ---------- 
----------------------------------------
*/

echo html_header('Suchfunktion');
?>

<div class = "outer">
	<h1>Suche</h1>

<!-- Suchfeld + Suchbutton -->
	<div class="panel">
		<form method="GET" id="form-suche">
			<table class="form">
				<tr>
					<td class="search-box-cell">
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

<!-- Filter -->
<div class = "sidebar">
	<div class = "panel filter">
		<form method="POST">
			<h2>Filtern nach...</h2>
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


				<button type="button" onclick="ajax_post();" class="full-width">Anwenden</button>
		</form>
	</div><!-- /panel -->
</div><!-- /sidebar -->
	
	

	<main class="container">
		<div class='panel'>
			<form method='POST'>
				<h2>Sortieren nach:</h2>
				<table class='liste'>
					<tr>

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
		// Hinzufügen einer CSS-Klasse zur Identifizierung der Sortierspalte
		if($_POST['sort'] == $value[value]){
			$active = ' active';
		}else{
			$active = "";
		}

		// Print einzelne Sortierfelder
		echo "
			<th>
				<button type='submit' name='sort' value='".$value[value]."' class='sort$active'>
					".$value[name]."
				</button>
			</th>
		";
	}
?>

					</tr>
				</table>
			</form>
		</div><!-- /panel -->


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

<div class="modal"> Place at bottom of page </div>

<script type = "text/javascript">

function ajax_post() {

	// http://stackoverflow.com/questions/9713058/sending-post-data-with-a-xmlhttprequest
	// Gute Infoquelle für ein POST Beispiel mit Ajax
	// Siehe vor allem die zweite Antwort mit FormData

	var data = new FormData();

	var ressorts = <?php echo json_encode($ressort); ?>;
	var huehue = 0;


	for (i = 0; i < ressorts.length; i++) { 
	data.append('f_ressort_list[]', document.getElementsByName('f_ressort_list[]')[huehue].checked);
	huehue++;
}

	data.append('f_position_list[]', document.getElementsByName('f_position_list[]')[0].checked);
	data.append('f_position_list[]', document.getElementsByName('f_position_list[]')[1].checked);
	data.append('f_position_list[]', document.getElementsByName('f_position_list[]')[2].checked);
	data.append('f_position_list[]', document.getElementsByName('f_position_list[]')[3].checked);

	data.append('f_status_list[]', document.getElementsByName('f_status_list[]')[0].checked);
	data.append('f_status_list[]', document.getElementsByName('f_status_list[]')[1].checked);




/*	var hr = new XMLHttpRequest();
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			setTimeout(function(){
				document.getElementsByTagName('body')[0].classList.remove('modal');
				//document.getElementById('list-container').classList.remove('modal');
			    alert(hr.responseText);
			}, 800);
		}
	};
	hr.open("POST", "http://neu.hhc-duesseldorf.de/wp-content/themes/twentyfourteen-child/functions/suchfunktion/AcceptAjax.php", true);
	var b = document.getElementsByTagName('body')[0];
	//var b = document.getElementById('list-container');
	b.className += " modal";
	hr.send(data);
*/


$.ajax({
  url: 'http://neu.hhc-duesseldorf.de/wp-content/themes/twentyfourteen-child/functions/suchfunktion/AcceptAjax.php',
  data: data,
  processData: false,
  contentType: false,
  type: 'POST',
  success: function(data){
    alert(data);
  }
});



}

</script>
 

<?php 
	// var_dump($filter);	
	echo html_footer();

?>

