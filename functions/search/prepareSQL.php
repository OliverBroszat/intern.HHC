<?php 
/* 
	prepareSQL.php 

	Enthält die Funktion prepareSQL($input), welche aus den Daten SQL Queries generiert.
	Zurückgegeben wird ein Array, der Form

	"contact_search" => "SELECT * FROM....",
	"mail" => "...",
	"phone" => "...",
	"address" => "...",
	"study" => "..."

	in welchem jeweils die SQL Anfragen als Strings gespeichert sind. Die Anfragen müssen
	mit $wpdb->prepare gebildet werden (SQL Injection!!).

*/


/* 
----------------------------------------
---------- Hilfsfunktionen ---------- 
----------------------------------------
*/

// ---------- prepare SELECT ----------
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


// ---------- prepare filter for WHERE----------
function sql_where_filter($filter){

	//WICHTIG: empty() wird auch für '0' true! Ersetze also durch == ''
	$all_empty = true;
	foreach ($filter as $key => $value) {
		if ($value[0]=='') {
			$filter[$key] = NULL;
		}
		else{
			$all_empty = false;
		}
	}

	if (!$all_empty) {
		global $wpdb;
		$sql = '';

		$first_call_a = true;
		foreach ($filter as $key => $list) {
			if ($list !== NULL) {
				if (!$first_call_a) {
					$sql .= " AND ";
				}
				$first_call_a = false;

				$sql .= "(";

				$first_call_b = true;
				foreach ($list as $value) {
					if (!$first_call_b) {
						$sql .= " OR ";
					}
					$first_call_b = False;
					
					// $sql .= $wpdb->prepare("%s = '%s'", $key, $value);
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


// ---------- prepare searchwords for WHERE ----------
function sql_where_search($search_words, $search_range){		
	/*
		Hiermit soll eine Abfrage folgenden Schemas generiert werden:
			
			(c1 LIKE '%w%1' OR c2 LIKE '%w%1' OR c3 LIKE '%w%1' ...) AND
			(c1 LIKE '%w%2' OR c2 LIKE '%w%2' OR c3 LIKE '%w%2' ...) AND
			(c1 LIKE '%w%3' OR c2 LIKE '%w%3' OR c3 LIKE '%w%3' ...)
			...
	*/
	if (array_filter($search_range)) {
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
	} else {
		$sql = 'True';
	}
	return $sql;
}




/* 
----------------------------------------
---------- Mittelfunktionen ---------- 
----------------------------------------
*/


function prepareSQL_contact_search($input, $search_select, $search_range){

	// ---------- Übergebene Werte ---------- 

	// prepare searchwords
	
	if (!is_array($input)) {
		// if $input is not an array, it is assumed that it should only be searched by ID
		$input = array(
			'search' => $input,
			'filter' => array(),
			'sort' => 'Contact.id',
			'order' => 'asc',
			'ajax_call' => false
		);
	}
	
	$search_words = preg_split("/[\s,]+/", trim($input['search']));

	// Sortieren
	if (empty($input['sort'])){					
		$sort = "Contact.last_name";
	}
	else {
		$sort = $input['sort'];
	}

	// ASC/DESC (wird in der SQL anfrage noch nicht berücksichtigt)
	if (empty($input['order'])){					
		$order = "ASC";
	}
	else{
		$order = $input['order'];
	}

	if(empty($input['filter'])){
		$filter = $input['filter'] = array();
	}

   
	// ---------- Datenabankabfrage vorbereiten ---------- 

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
			".sql_where_filter($input['filter'])." AND
			(".sql_where_search($search_words, $search_range).")
		GROUP BY 
			Contact.id
		ORDER BY 
			$sort $order, Contact.last_name $order, Contact.first_name $order, Ressort.name $order
	";
	return $sql;
}


/* 
----------------------------------------
---------- Hauptfunktion ---------- 
----------------------------------------
*/

function prepareSQL(
	$input, 
	$search_select = array(
		'Contact' => array(
			'id',
			'prefix',
			'first_name',
			'last_name',
			'birth_date',
			'comment',
			'skype_name'
		),
		'Ressort' => array(
			'name'
		),
		'Member' => array(
			'active',
			'position',
			'joined',
			'left'
		)
	), 
	$search_range = array(
		'Contact' => array(
			'id'
		)
	)
) {
	
	$queries = array(
		'contact_search' => prepareSQL_contact_search($input, $search_select, $search_range),
		'mail' => '',
		'phone' => '',
		'address' => '',
		'study' => ''
	);

	return $queries;
}

?>