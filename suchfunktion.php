<?php
/**
 * Template Name: Suchfunktion
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

global $wpdb;

echo html_header('Suchfunktion');

?>

<h1>Suche</h1>

<!-- Suchfeld + Suchbutton -->
<div class="panel full-width">
	<form method="GET">
		<table class="form">
			<tr>
				<td style="vertical-align: middle;">
					<input type="text" name="search_text" placeholder="Suchtext eingeben...">
				</td>
				<td>
					<button class='search'>Suchen</button>
				</td>
			</tr>
		</table>
	</form>
</div><!-- /panel -->


<div class = "outer">
<div class = "sidebar">
	<!-- panel 1: filter-ressort -->
	<div class = "panel">
		<form>
			<table>
				<tr>
					<th colspan="2">
						Filter nach Ressort:<br>
					</th>
				</tr>
				<tr>
					<td>

<?php 
	// Ressort Checkboxen
	$ressort = $wpdb->get_results("SELECT name FROM Ressort");
	for ($i = 0; $i < (sizeof($ressort)/2); $i++) {
		echo "<input type='checkbox' name='ressort' value='".$ressort[$i]->name."' checked> ".uppercase($ressort[$i]->name)."<br>";
	}
	echo "</td><td>";
	for ($i; $i < (sizeof($ressort)); $i++) {
		echo "<input type='checkbox' name='ressort' value='".$ressort[$i]->name."' checked> ".uppercase($ressort[$i]->name)."<br>";
	}
?>	

						</td>
					</tr>
				</table>
			</form>
		</div><!-- /panel -->

		<!-- panel 2 -->
		<div class = "panel">
			<form>
				<table>
					<tr>
						<th colspan="2">
							Filter nach XYZ:<br>
						</th>
					</tr>
					<tr>
						<td>
							<input type='checkbox' name='xyz' value='x' checked> X<br>
							<input type='checkbox' name='xyz' value='y' checked> Y<br>
							<input type='checkbox' name='xyz' value='z' checked> Z<br>
						</td>
						<td>
							<input type='checkbox' name='xyz' value='x' checked> X<br>
							<input type='checkbox' name='xyz' value='y' checked> Y<br>
							<input type='checkbox' name='xyz' value='z' checked> Z<br>
						</td>
					</tr>
				</table>
			</form>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	
	<main class="container">


<?php

	//Suchfeld
	require_once('wp-content/themes/twentyfourteen-child/templates/member_search.php');
	$output = member_search($_GET['search_text'],$_POST['sort']);

	// Beginn Tabelle für Sucheregbnisse
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
						// Print Search Results
						foreach ($output as $row) {
							echo "<tr><td>".getListEntryHTML($row->id, $row)."</td></tr>";
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