<?php 
/* 
Suchfunktion
Bearbeiter: Daniel Högel
Status: 25.03.2016, 18:03 Uhr
*/


require_once('../../../../wp-config.php');
global $wpdb;

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
	<main style="max-width: 720px; margin: 0 auto;">
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
				<tr>
					<td>
						
					
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
	$ressort = $wpdb->get_results("SELECT name FROM Ressort");
	for ($i = 0; $i < (sizeof($ressort)/2); $i++) {
		echo "<input type='checkbox' name='ressort' value='".$ressort[$i]->name."' checked> ".$ressort[$i]->name."<br>";
	}
	echo "</td><td>";
	for ($i; $i < (sizeof($ressort)); $i++) {
		echo "<input type='checkbox' name='ressort' value='".$ressort[$i]->name."' checked> ".$ressort[$i]->name."<br>";
	}
?>									
					</td>
				</tr>
			</table>		
		</form>

<?php 


	echo"<br><br>";
	echo "Suche nach: ";
	$search = explode(" ", trim($_GET['search_text']));
	foreach ($search as $value) {
		echo $value.";";
	}
	echo"<br><br>";

	for ($i=0; $i < sizeof($search); $i++) { 
		$search_for_id .= "id LIKE '%".$search[$i]."%'";
		$search_for_first_name .= "first_name LIKE '%".$search[$i]."%'";
		$search_for_last_name .= "last_name LIKE '%".$search[$i]."%'";
		$search_for_birth_date .= "birth_date LIKE '%".$search[$i]."%'";
		if ($i<(sizeof($search) - 1)) {
			$search_for_id .= " OR ";
			$search_for_first_name .= " OR ";
			$search_for_last_name .= " OR ";
			$search_for_birth_date .= " OR ";
		}

	}

	// Sortieren
	if (isset($_POST['sort'])){					
		$order = $_POST['sort'];
	}
	else{
		$order = "id";
	}
	echo "Sortieren nach: ";
	echo $order;
	echo"<br><br>";

	
	$results = $wpdb->get_results("
		SELECT 
			id, first_name, last_name, birth_date
		FROM 
			Contact
		WHERE		
			$search_for_id OR 
			$search_for_first_name OR 
			$search_for_last_name OR 
			$search_for_birth_date
		ORDER BY $order
		");

	//var_dump($results);
	echo"<br><br>";





	$output = $results;

	// Geburtsdatum in Alter umwandeln
	for ($i = 0; $i < sizeof($output); $i++) {		
		$date1 = date_create($output[$i]->birth_date);
		$date2 = date_create("now");
		$alter = date_diff($date1, $date2);
		$output[$i]->birth_date = $alter->format('%y');
	}
?>

		<h2>Suchergebnisse</h2>
		<form method="POST">
			<table class='liste' style="width:720px;">
				<tr>
					<th>
						<button type="submit" name="sort" value="id" class="sort <?php if($order == 'id'){echo 'active';} ?>">
							ID
						</button>
					</th>
					<th>
						<button type="submit" name="sort" value="first_name" class="sort <?php if($order == 'first_name'){echo 'active';} ?>" >
							Vorname
						</button>
					</th>
					<th>
						<button type="submit" name="sort" value="last_name" class="sort <?php if($order == 'last_name'){echo 'active';} ?>" >
							Nachname
						</button>
					</th>
					<th>
						<button type="submit" name="sort" value="birth_date" class="sort <?php if($order == 'birth_date'){echo 'active';} ?>" >
							Alter
						</button>
					</th>
				</tr>
<?php 
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