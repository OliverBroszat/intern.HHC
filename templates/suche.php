<?php
/**
 * Template Name: Suche
 * Author: Daniel
 * Status: 06.04.2016, 15:30 Uhr
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

// Spalten und Filter für die Suchanfrage vorbereiten
//$columns = array('Contact.first_name', 'Contact.last_name');

$search_range = array(
	'Contact' => array(
		'first_name',
		'last_name',
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


$filter = array(
	"Ressort.name" => $_POST['f_ressort_list'],
	"Member.position" => $_POST['f_position_list'],
	"Member.active" => $_POST['f_status_list']
);

// Suchanfrage in SQL (member_search.php)
$result = member_search($search_range, $_GET['search_text'], $filter, $_POST['sort']);


echo html_header('Suchfunktion');
?>


<div class = "outer">
	<h1>Suche</h1>

<!-- Suchfeld + Suchbutton -->
	<div class="panel">
		<form method="GET">
			<table class="form">
				<tr>
					<td style="vertical-align: middle;">
						<input type="text" name="search_text" value="<?php echo htmlspecialchars($_GET['search_text']);?>"placeholder="Suche...">
					</td>
					<td>
						<button class='search'>Suchen</button>
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


				<button class="full-width">Anwenden</button>
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
				<table class='liste search_results'>

<?php
	// echo '<br><br>';
	// var_dump($result);
	// echo '<br><br>';

	// Print Search Results 
	$number = 1;
	foreach ($result as $row) {
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