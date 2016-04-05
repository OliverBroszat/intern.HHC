<?php 

// ----------------------------------------------------------

	function sql_where_filter($filter){
		$output = '';
		
		echo "<br><br><br>Filter: ".var_dump($filter);

		$filter_keys = array_keys($filter);
		echo "<br><br><br>Keys: ".var_dump($filter_keys);

		$output .= '(';

		for ($i = 0; $i < sizeof($filter); $i++) {
			if($filter->$filter_keys[$i] != ""){
				
				$output .= "$filter_keys[$i] = '".$filter->$filter_keys[$i]."'";	
				
				if (($i < (sizeof($filter) - 1)) && ($filter->$filter_keys[$i] != "")) {
					$output .= ' AND ';
				}
			}
		}

		$output .= ')';

/*		
		$filter_keys = array_keys($filter);
		$j = 0;
		foreach ($filter_keys as $key) {	
			$output .= '(';
			$i = 0;



			// Filter sind Arrays
			foreach ($filter[$key] as $value) {
				$output .= "$key = '".$value."'";

				if ($i < (sizeof($filter[$key]) - 1)) {
					$output .= ' OR ';
				} 
				else{
					$output .= ')';
				}
				$i ++;
			}
			if ($j < (sizeof($filter_keys) - 1)) {
				$output .= ' AND';
			}
	
			$j ++;
		}
*/
		return $output;
	}

// ----------------------------------------------------------

	function sql_where_search($search_words, $columns){		
		/*
			Hiermit soll eine Abfrage folgenden Schemas generiert werden:
				
				(c1 LIKE '%w%1' OR c2 LIKE '%w%1' OR c3 LIKE '%w%1' ...) AND
				(c1 LIKE '%w%2' OR c2 LIKE '%w%2' OR c3 LIKE '%w%2' ...) AND
				(c1 LIKE '%w%3' OR c2 LIKE '%w%3' OR c3 LIKE '%w%3' ...)
				...
		*/

		$sql = '';

		for ($j = 0; $j < count($search_words); $j++) {
			$sql .= '(';

			for ($i = 0; $i < count($columns); $i++) {
				$sql .= "$columns[$i] LIKE '%$search_words[$j]%'";

				if($i < (count($columns) - 1)){
					$sql .= ' OR ';
				}
			}
			$sql .= ')';

			if($j < (count($search_words) - 1)){
				$sql .= ' AND ';
			}
		}

		return $sql;
	}

// ----------------------------------------------------------

	function where_sql($search_words, $columns, $filter){
		$output = '';
		$output .= sql_where_filter($filter);
		$output .= ' AND (';

		$output .= sql_where_search($search_words, $columns);
		$output .= ')';
		return $output;
	}

// ----------------------------------------------------------

	function member_search($columns, $search_text, $filter, $sort){
		global $wpdb;

		// Spalten anzeigen
		echo"<br><b>Suche in: </b>";
		foreach ($columns as $value) {
			echo "<i>$value</i>; ";
		}


		// Suchworte Trennen und auf die ersten drei Worte begrenzen
		$search_words = array_slice(explode(" ", trim($search_text)), 0, 3);
		echo"<br><b>Suche nach:</b> ";
		
		foreach ($search_words as $value) {
			echo "<i>$value</i>; ";
		}


		// Filter anzeigen
		echo"<br><b>Filtern nach: </b>";	
		foreach ($filter as $key) {
			$category = array_search($key, $filter);
			echo "<i>$category = $key</i>; ";

		/*	foreach ($key as $value) {				
				echo "<i>$category = $value</i>; ";
			}	
		*/
		}


		// Sortieren
		if (isset($sort)){					
			$order = $sort;
		}
		else{
			$order = "Contact.last_name";
		}
		echo "<br><b>Sortieren nach: </b><i>$order</i>";
	   

// ---------- Datenabankabfrage ---------- 
		$sql = "
			SELECT 
				Contact.id, Contact.first_name, Contact.last_name, Contact.birth_date, Ressort.name, Member.active, Member.position, Member.joined, Member.left
			FROM 
				Contact
			JOIN 
				Member ON Contact.id = Member.contact
			JOIN 
				Ressort ON Member.ressort = Ressort.id
			WHERE ".
				where_sql($search_words, $columns, $filter)."
			ORDER BY 
				$order, Contact.last_name, Contact.first_name, Ressort.name
		";
		echo "<br><br><b>SQL Anfrage: </b><br>";
		echo"$sql<br><br>";

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