<?php 
/*
	Hier werden verschiedene universelle Funktionen ausgelagert.
*/


//	Funktion um einen String in Großbuchstaben zu übertragen.
//	Erster Buchstabe uppercase, bei Wörtern < 3 Zeichen alles zu uppercase setzen
if (!function_exists('uppercase')) {
	function uppercase($string) {
		if(strlen($string)<4) {
			return strtoupper($string);
		} else{
			return ucfirst($string);
		}
	}
}

//	Funktion um das Datum umzuformatieren
//	von YYYY-MM-DD zu DD.MM.YYYY umwandeln
if (!function_exists('change_date_format')) {
	function change_date_format($origDate) {
		if($origDate == "0000-00-00") {
			return "-";
		}elseif ($origDate == "") {
			return "";
		}else{
			$newDate = date("d.m.Y", strtotime($origDate));
			return $newDate;
		}
	}
}

//	Funktion um zu testen ob ein Filterkriterium ausgewählt wurde.
//	 ......... mehr beschreibung!!!!
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

?>