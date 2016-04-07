<?php 

// ----------------------------------------------------------

function sql_where_filter($filter){
	// echo "<br><hr>";
	// echo "<b>Filter: </b>";
	// var_dump($filter);
	// echo "<br>";

	if (array_filter($filter)) {
		$output = '';

		$first_call_a = True;
		foreach ($filter as $key => $list) {
			if ($list !== NULL) {
				if (!$first_call_a) {
					$output .= " AND ";
				}
				$first_call_a = False;

				$output .= "(";

				$first_call_b = True;
				foreach ($list as $value) {
					if (!$first_call_b) {
						$output .= " OR ";
					}
					$first_call_b = False;
					
					$output .= "$key = '$value'";
				}
				$output .= ")";
			}
		}	
	} else{
		$output = 'True';
	}

	// echo "<br><br><b>Output: </b><br>";
	// var_dump($output);
	// echo "<br>";

	// echo "<br><hr>";
	return $output;
}

// ----------------------------------------------------------

function sql_where_search($search_words, $search_range){		
	/*
		Hiermit soll eine Abfrage folgenden Schemas generiert werden:
			
			(c1 LIKE '%w%1' OR c2 LIKE '%w%1' OR c3 LIKE '%w%1' ...) AND
			(c1 LIKE '%w%2' OR c2 LIKE '%w%2' OR c3 LIKE '%w%2' ...) AND
			(c1 LIKE '%w%3' OR c2 LIKE '%w%3' OR c3 LIKE '%w%3' ...)
			...
	*/

	$sql = '';

	// Jedes Suchwort
	$first_loop_a = true;
	foreach($search_words as $word) {
		
		if(!$first_loop_a) {
			$sql .= ' AND ';
		}
		$first_loop_a = false;

		$sql .= '(';
		// Jede Tabelle
		$first_loop_b = true;
		foreach($search_range as $table => $columns) {
			
			// Jede Spalte der Tabelle
			foreach($columns as $column) {
				
				if(!$first_loop_b) {
					$sql .= ' OR ';
				}
				$first_loop_b = false;
				$sql .= "$table.$column LIKE '%".$word."%'";
			}
		}
		$sql .= ')';
	}

	return $sql;
}

// ----------------------------------------------------------

function where_sql($search_words, $search_range, $filter){
	$output = '';
	$output .= sql_where_filter($filter);
	$output .= ' AND (';

	$output .= sql_where_search($search_words, $search_range);
	$output .= ')';
	return $output;
}

// ----------------------------------------------------------

function member_search($select_sql, $search_range, $search_text, $filter, $sort){
	global $wpdb;

	// Spalten anzeigen
	// echo"<br><b>Suche in: </b>";
	// $first_call = true;
	// foreach ($columns as $value) {
	// 	if(! $first_call){
	// 		echo '; ';
	// 	}
	// 	$first_call = false;
	// 	echo "<i>$value</i>";
	// }

	// Suchworte Trennen und auf die ersten drei Worte begrenzen
	// $search_words = array_slice(explode(" ", trim($search_text)), 0, 3);
	$search_words = explode(" ", trim($search_text));
	// echo"<br><b>Suche nach: </b> ";
	// $first_call = true;
	// foreach ($search_words as $value) {
	// 	if(! $first_call){
	// 		echo '; ';
	// 	}
	// 	$first_call = false;
	// 	echo "<i>$value</i>";

	//}

	// Filter anzeigen
	// echo '<br><b>Filter nach: </b><br>';
	// foreach ($filter as $key => $value) {
	// 	if(! empty($value)){
	// 		echo " - $key = ";

	// 		$first_call = true;
	// 		foreach ($value as $item) {
	// 			if(! $first_call){
	// 				echo '; ';
	// 			}
	// 			$first_call = false;
	// 			echo "<i>$item</i>";
	// 		}
	// 		echo '<br>';	
	// 	}
	// }


	// Sortieren
	if (!empty($sort)){					
		$order = $sort;
	}
	else{
		$order = "Contact.last_name";
	}
	// echo "<br><b>Sortieren nach: </b><i>$order</i>";
   

// ---------- Datenabankabfrage ---------- 
	$sql = "
		SELECT 
			$select_sql
		FROM 
			Contact
		JOIN 
			Member ON Contact.id = Member.contact
		JOIN 
			Ressort ON Member.ressort = Ressort.id
		JOIN
			Address ON Contact.id = Address.contact
		JOIN
			Mail ON Contact.id = Mail.contact
		JOIN
			Phone ON Contact.id = Phone.contact
		JOIN
			Study ON Contact.id = Study.contact
		WHERE ".
			where_sql($search_words, $search_range, $filter)."
		GROUP BY 
			Contact.id
		ORDER BY 
			$order, Contact.last_name, Contact.first_name, Ressort.name
	";
	// echo "<br><br><b>SQL Anfrage: </b><br>";
	// echo"$sql<br><br>";

	// return $sql;

	$results = $wpdb->get_results($sql);


// ---------- Suchergebnisse anpassen ---------- 
	
	// Ergebnis der Datenbankabfrage von den Inhalten, die tatsächlich ausgegeben werden trennen
	$output = $results;

	// Inhalte umwandeln
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

		//Position uppercase
		$output[$i]->position = uppercase($output[$i]->position);

		// Daten formatieren
		$output[$i]->joined = change_date_format($output[$i]->joined);
		$output[$i]->left = change_date_format($output[$i]->left);
	}

	return $output;
}