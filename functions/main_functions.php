<?php 
/*
	Hier werden verschiedene kleinere Funktionen ausgelagert.
*/

// --------- HTML Footer --------
function html_footer(){
	return "
			</body>
		</html>
	";
}


// -------- Erster Buchstabe uppercase, bei Wörtern < 3 Zeichen alles uppercase ---------
if (!function_exists('uppercase')) {
	function uppercase($string){
		if(strlen($string)<3){
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

?>