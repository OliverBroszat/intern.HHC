<?php 



// ----------------------------------------------------------

function sql_select($search_select){
	$sql = '';

	$first_loop = true;
	foreach($search_select as $table => $columns) {
		foreach($columns as $column) {		
			if(!$first_loop) {
				$sql .= ', ';
			}
			$first_loop = false;

			$sql .= "$table.$column";
		}
	}
	return $sql;
}

// ----------------------------------------------------------

function sql_where_filter($filter){
	if (array_filter($filter)) {
		$sql = '';

		$first_call_a = True;
		foreach ($filter as $key => $list) {
			if ($list !== NULL) {
				if (!$first_call_a) {
					$sql .= " AND ";
				}
				$first_call_a = False;

				$sql .= "(";

				$first_call_b = True;
				foreach ($list as $value) {
					if (!$first_call_b) {
						$sql .= " OR ";
					}
					$first_call_b = False;
					
					$sql .= "$key = '$value'";
				}
				$sql .= ")";
			}
		}	
	} else{
		$sql = 'True';
	}

	return $sql;
}

// ----------------------------------------------------------

function getDetail($contact_id) {
	/*
		Fetch detail information from database, such as phone
		number, mail addresses and other stuff...
	*/
	global $wpdb;
	$detail_array = array();
	$detail_array['phone'] = $wpdb->get_results("
		SELECT number FROM Phone WHERE contact=$contact_id;
	");
	$detail_array['mail'] = $wpdb->get_results("
		SELECT address FROM Mail WHERE contact=$contact_id;
	");
	$detail_array['address'] = $wpdb->get_results("
		SELECT * FROM Address WHERE contact=$contact_id;
	");
	$detail_array['study'] = $wpdb->get_results("
		SELECT * FROM Study WHERE contact=$contact_id;
	");
	return $detail_array;
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

function member_search($search_select, $search_range, $search_text, $filter, $search_order){
	global $wpdb;

	$search_words = explode(" ", trim($search_text));

	// Sortieren
	if (empty($search_order)){					
		$search_order = "Contact.last_name";
	}
   
// ---------- Datenabankabfrage ---------- 
	$sql = "
		SELECT 
			".sql_select($search_select)."
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
		WHERE
			".sql_where_filter($filter)." AND
			(".sql_where_search($search_words, $search_range).")
		GROUP BY 
			Contact.id
		ORDER BY 
			$search_order, Contact.last_name, Contact.first_name, Ressort.name
	";

	$results = $wpdb->get_results($sql);

// ---------- Suchergebnisse anpassen ----------
//		Suchergebnisse werden angepasst und zusammen mit Detail-Informationen
//		in einem Array der Form ('info' => suchergebnis, 'detail' => Detailinfos)
//		gespeichert
	
	// Ergebnis der Datenbankabfrage von den Inhalten, die tatsächlich ausgegeben werden trennen
	$output = $results;
	$final = array();

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
		
		// Get detail information
		$detail = getDetail($output[$i]->id);
		$final[$i] = array(
			'info' => $output[$i],
			'detail' => $detail
		);
	}
	return $final;
	var_dump($_POST);
}