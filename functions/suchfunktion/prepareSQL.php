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


// ---------- prepare searchwords for WHERE ----------
function sql_where_search($search_words, $search_range){	

	global $wpdb;

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
				
				$sql .= $wpdb->prepare("$table.$column LIKE %s", '%'.$word.'%');
			}
		}
		$sql .= ')';
	}

	return $sql;
}




/* 
----------------------------------------
---------- Mittelfunktionen ---------- 
----------------------------------------
*/


function prepareSQL_contact_search($input){

	global $wpdb;


	// ---------- Vordefinierte Werte ---------- 

	// Spalten, die ausgewählt werden
	$search_select = array(
		'Contact' => array(
			'id',
			'first_name',
			'last_name',
			'birth_date',
			'comment'
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
	);


	// Spalten, nach denen gesucht werden kann
	$search_range = array(
		'Contact' => array(
			'first_name',
			'last_name'
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


	// ---------- Übergebene Werte ---------- 

	// prepare searchwords
	$search_words = explode(" ", trim($input['search']));

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

   
	// ---------- Datenabankabfrage vorbereiten ---------- 
	
        
        	$sql = "
		SELET 
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
			$sort, Contact.last_name, Contact.first_name, Ressort.name
	";



	return $sql;
}


/* 
----------------------------------------
---------- Hauptfunktion ---------- 
----------------------------------------
*/

function prepareSQL($input){
	
	$queries = array(
		'contact_search' => prepareSQL_contact_search($input),
		'mail' => '',
		'phone' => '',
		'address' => '',
		'study' => ''
	);

	return $queries;
}

?>