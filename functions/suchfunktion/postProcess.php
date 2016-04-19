<?php 
/* 
	postProcess.php 

	Bearbeitet einzelne Einträge wie Alter, Aktivität und Groß- bzw. Kleinschreibung nach, da Daten in der DB nicht zwingend Nutzerfreundlich gespeichert werden.
*/



function postProcess($data){
	
	$final = $data;

	// Inhalte umwandeln

	foreach ($data as $id => $row) {

		// Geburtsdatum in Alter umwandeln
		$date1 = date_create($row['info']->birth_date);
		$date2 = date_create("now");
		$alter = date_diff($date1, $date2);

		$final[$id]['info']->birth_date = $alter->format('%y');

		// HHC Status
		if($row['info']->active == 0){
			$final[$id]['info']->active = "Aktiv";
		} else{
			$final[$id]['info']->active = "Passiv";
		}

		// Ressort uppercase
		$final[$id]['info']->name = uppercase($row['info']->name);

		//Position uppercase
		$final[$id]['info']->position = uppercase($row['info']->position);

		// Daten formatieren
		$final[$id]['info']->joined = change_date_format($row['info']->joined);
		$final[$id]['info']->left = change_date_format($row['info']->left);
		$final[$id]['detail']['image_html'] = $data[$id]['detail']['image_html'];
	}
	
	return $final;
}

?>
