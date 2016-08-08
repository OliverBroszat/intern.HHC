<?php 
/*
	Hier werden verschiedene universelle Funktionen ausgelagert.
*/


// -------- Erster Buchstabe uppercase, bei Wörtern < 3 Zeichen alles uppercase ---------
if (!function_exists('uppercase')) {
	function uppercase($string){
		if(strlen($string)<4){
			return strtoupper($string);
		} else{
			return ucfirst($string);
		}
	}
}


// --------- change the date from YYYY-MM-DD to DD.MM.YYYY ----------
if (!function_exists('change_date_format')) {
	function change_date_format($origDate){
		if($origDate == "0000-00-00"){
			return "-";
		}elseif ($origDate == "") {
			return "";
		}else{
			$newDate = date("d.m.Y", strtotime($origDate));
			return $newDate;
		}
	}
}

// --------- Prüfe, ob ein Filter angewählt wurde ----------
if (!function_exists('check')) {
	function check($key, $value){
		global $input;

		$filter = $input['filter'];


		if (!empty($filter[$key])) {	
			$find = array_search($value, $filter[$key]);
			
			if ($find !== False) {
				return " checked ";	
			}
		}
	}
}	

// --------- Wandelt 0/1 zu aktiv/inaktiv um ----------
function bool_to_lbl($boolean){
	if 		($boolean == '0') 	{ return 'aktiv'; }
	elseif 	($boolean == '1') 	{ return 'inaktiv'; }
	else 						{ return $boolean; }
}

// --------- Wandelt eine stdClass in ein Array ohne Dublikate um ----------
function res_to_array($results){
	$array = array();
	foreach ($results as $index) {
		foreach ($index as $value) {
			array_push($array, $value);
		}	
	}
	return array_values(array_unique($array));
}
?>