<?php
/**
 * Template Name: Suche
 * Author: Daniel
 * Status: 02.04.2016, 16:00 Uhr
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */


require_once('wp-config.php');
require_once('wp-load.php');
require_once('wp-content/themes/twentyfourteen-child/templates/functions.php');
require_once('wp-content/themes/twentyfourteen-child/templates/list_entry.php');
require_once('wp-content/themes/twentyfourteen-child/templates/member_search.php');

global $wpdb;

echo html_header('Suchfunktion');

/*
echo "<br>vd Filter: <br>";
var_dump($_GET['check_list']);

$ressort_list = $_GET['f_position_ressort[]'];
foreach ($ressort_list as $value) {
	echo $value."<br>";
}
*/
?>

<div class = "outer">

	<h1>Suche</h1>

	<!-- Suchfeld + Suchbutton -->
	<div class="panel">
		<form method="GET">
			<table class="form">
				<tr>
					<td style="vertical-align: middle;">
						<input type="text" name="search_text" placeholder="Suche... (Vorame und/oder Nachname)">
					</td>
					<td>
						<button class='search'>Suchen</button>
					</td>
				</tr>
			</table>
		</form>
	</div><!-- /panel -->

<div class = "sidebar">
	
<!-- Filter -->
	<div class = "panel filter">
		<form>
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
		echo "<label><input type='checkbox' name='check_list[]' value='".$ressort[$i]->name."'".check('f_ressort', $ressort[$i]->name)."> ".uppercase($ressort[$i]->name)."</label><br>";
	}
	echo "</td><td>";
	for ($i; $i < (sizeof($ressort)); $i++) {
		echo "<label><input type='checkbox' name='check_list[]' value='".$ressort[$i]->name."'".check('f_ressort', $ressort[$i]->name)."> ".uppercase($ressort[$i]->name)."</label><br>";
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
							<label><input type='checkbox' name='f_position[0]' value='anwärter' <?php echo check('f_position', 'anwärter'); ?>> Anwärter</label><br>
							<label><input type='checkbox' name='f_position[1]' value='mitglied' <?php echo check('f_position', 'mitglied'); ?>> Mitglied</label><br>
						</td>
						<td>
							<label><input type='checkbox' name='f_position[2]' value='ressortleiter' <?php echo check('f_position', 'ressortleiter'); ?>> Ressortleiter</label><br>
							<label><input type='checkbox' name='f_position[3]' value='alumni' <?php echo check('f_position', 'alumni'); ?>> Alumni</label><br>
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
							<label><input type='checkbox' name='f_active' value='0' <?php echo check('f_active', '0'); ?>> Aktiv</label><br>
						</td>
						<td>
							<label><input type='checkbox' name='f_active' value='1' <?php echo check('f_active', '1'); ?>> Inaktiv</label><br>
						</td>
					</tr>
				</table>


				<button class="full-width">Anwenden</button>
			</form>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	
	<main class="container">


<?php
// ---------- Suchfeld ---------- 
	// Spalten und Filter für die Suchanfrage vorbereiten
	$columns = array('Contact.first_name', 'Contact.last_name');
	
	$filter = array(
		'Ressort.name' => $_GET['f_ressort'],
		'Member.position' => $_GET['f_position'],
		'Member.active' => $_GET['f_active']

	);

	echo "<br><br><br>";
	var_dump($filter);
	echo "<br><br><br>";

	// Suchanfrage in SQL (member_search.php)
	$output = member_search($columns, $_GET['search_text'], $filter, $_POST['sort']);


// ---------- Beginn Tabelle für Sucheregbnisse ---------- 
	echo "
		<div class='panel'>
		<form method='POST'>
			<h2>Sortieren nach:</h2>
			<table class='liste'>
				<tr>
		";

	// Table Header
	$t_header = array(
		array('value' => 'Contact.id', 'name' => 'ID'),
		array('value' => 'Contact.first_name', 'name' => 'Vorname'),
		array('value' => 'Contact.last_name', 'name' => 'Nachname'), 
		array('value' => 'Contact.birth_date', 'name' => 'Alter'), 
		array('value' => 'Ressort.name', 'name' => 'Ressort'),
		array('value' => 'Member.active', 'name' => 'Status')
	);	

	// Print Tabelle Header
	foreach ($t_header as $value) {
		
		// Hinzufügen einer CSS-Klasse zur Identifizierung der Sortierspalte
		if($_POST['sort'] == $value[value]){
			$active = ' active';
		}else{
			$active = "";
		}

		// Print einzelne Header-Zellen
		echo "
			<th>
				<button type='submit' name='sort' value='".$value[value]."' class='sort$active'>
					".$value[name]."
				</button>
			</th>
		";
	}

	echo "</tr>";
?>
				</table>
			</form>
		</div><!-- /panel -->

		<div class='panel'>
			<form method='POST'>
				<h2>Suchergebnisse</h2>
				<table class='liste search_results'>
					<?php
// ---------- Print Search Results ---------- 
						$number = 1;
						foreach ($output as $row) {
							echo "<tr><td>".getListEntryHTML($number, $row)."</td></tr>";
							$number ++;
						}
					?>
				</table>
			</form>
		</div><!-- /panel -->	
	</main>
	</div><!-- /outer -->
<?php 
	echo html_footer();
?>