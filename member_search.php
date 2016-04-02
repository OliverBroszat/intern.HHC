<?php 
	function member_search($search_text, $sort){
		global $wpdb;

		// Suchworte Trennen
		echo"<br><b>Suche nach:</b> ";
		$search = explode(" ", trim($search_text));
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
		if (isset($sort)){					
			$order = $sort;
		}
		else{
			$order = "Contact.id";
		}
		echo "&nbsp;&nbsp;&nbsp; <b>Sortieren nach:</b> <i>$order</i>";
		echo"<br><br>";


		// Datenabankabfrage
		$results = $wpdb->get_results("
			SELECT 
				Contact.id, Contact.first_name, Contact.last_name, Contact.birth_date, Ressort.name, Member.active, Member.position, Member.joined, Member.left
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

			//Position uppercase
			$output[$i]->position = uppercase($output[$i]->position);

			// Daten formatieren
			$output[$i]->joined = change_date_format($output[$i]->joined);
			$output[$i]->left = change_date_format($output[$i]->left);

			// Nummerierung hinzufügen
			//$output[$i] = array('nr' => $i) + $output[$i];
		}

		return $output;
	}