<?php 
/* 
Suchfunktion
Bearbeiter: Daniel Högel
Status: 26.03.2016, 16:00 Uhr
*/


require_once('../../../../wp-config.php');
global $wpdb;

// Erster Buchstabe uppercase, bei Wörtern < 3 Zeichen alles uppercase
function uppercase($string){
	if(strlen($string)<3){
		return strtoupper($string);
	} else{
		return ucfirst($string);
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
	<title>Suchfunktion</title>
</head>
<body>
	<main style="max-width: 720px; margin: 0 auto 10rem;">

<!-- Suchfeld -->
		<h1>Suche</h1>
		<form method="GET">
			<table class="form">
				<tr>
					<td style="vertical-align: middle;">
						<input type="text" name="search_text" placeholder="Suchtext eingeben...">
					</td>
					<td>
						<button type='submit' class='search'>Suchen</button>
					</td>
				</tr>
			</table>
			
			<table style="margin: 1rem 0;">
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

<?php 
	// Suchworte Trennen
	echo"<br><b>Suche nach:</b> ";
	$search = explode(" ", trim($_GET['search_text']));
	foreach ($search as $value) {
		echo "<i>$value</i>;";
	}

	// Suchanfragen für jedes Wort in jeder Spalte vorbereiten
	for ($i=0; $i < sizeof($search); $i++) { 
		$search_for_id .= "Contact.id LIKE '%".$search[$i]."%'";
		$search_for_first_name .= "Contact.first_name LIKE '%".$search[$i]."%'";
		$search_for_last_name .= "Contact.last_name LIKE '%".$search[$i]."%'";
		$search_for_birth_date .= "Contact.birth_date LIKE '%".$search[$i]."%'";
		$search_for_ressort_name .= "Ressort.name LIKE '%".$search[$i]."%'";
		if ($i<(sizeof($search) - 1)) {
			$search_for_id .= " OR ";
			$search_for_first_name .= " OR ";
			$search_for_last_name .= " OR ";
			$search_for_birth_date .= " OR ";
			$search_for_ressort_name .= " OR ";
		}

	}


	// Sortieren
	if (isset($_POST['sort'])){					
		$order = $_POST['sort'];
	}
	else{
		$order = "Contact.id";
	}
	echo "&nbsp;&nbsp;&nbsp; <b>Sortieren nach:</b> <i>$order</i>";
	echo"<br><br>";


	// Datenabankabfrage
	$results = $wpdb->get_results("
		SELECT 
			Contact.id, Contact.first_name, Contact.last_name, Contact.birth_date, Ressort.name, Member.active
		FROM 
			Contact
		JOIN 
			Member ON Contact.id = Member.contact
		JOIN 
			Ressort ON Member.ressort = Ressort.id
		WHERE		
			$search_for_id OR 
			$search_for_first_name OR 
			$search_for_last_name OR 
			$search_for_birth_date OR
			$search_for_ressort_name

		ORDER BY $order,Contact.last_name,Contact.first_name,Ressort.name
	");



	// Ergebnis der Datenbankabfrage von den Inhalten, die tatsächlich ausgegeben werden trennen
	$output = $results;

	// Suchergebnisse anpassen
	for ($i = 0; $i < sizeof($output); $i++) {		
		
		// Geburtsdatum in Alter umwandeln
		$date1 = date_create($output[$i]->birth_date);
		$date2 = date_create("now");
		$alter = date_diff($date1, $date2);
		$output[$i]->birth_date = $alter->format('%y');

		// HHC Status
		if($output[$i]->active == 0){
			$output[$i]->active = "Aktiv";
		} else{
			$output[$i]->active = "Passiv";
		}

		// Ressort uppercase
		$output[$i]->name = uppercase($output[$i]->name);

		// Nummerierung hinzufügen
		//$output[$i] = array('nr' => $i) + $output[$i];
	}


	// Beginn Tabelle für Sucheregbnisse
	echo "
		<h2>Suchergebnisse</h2>
		<form method='POST'>
			<table class='liste' style='width:720px;'>
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
		if($order == $value[value]){
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
	

	// Print Search Results
	foreach ($output as $row) {
		echo "<tr>";
			foreach ($row as $col) {
				echo "<td>$col</td>";
			}
		echo "</tr>";
	}
?>
		
			</table>
		</form>
	</main>

</body>
</html>