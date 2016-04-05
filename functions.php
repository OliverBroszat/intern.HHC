<?php 
/*
	Hier werden verschiedene kleinere Funktionen ausgelagert.

	Übersicht:
		html_header($title)
			- Beschreibung: Öffnet das HTML Dokument, setzt die meta-Tags, verlinkt die Stylesheets
			- Argumente:	$title = Seitentitel
			- Return: 		HTML-Code
		
		html_footer()
			- Beschreibung: Schließt das HTML Dokument
			- Argumente: 	-
			- Return: 		HTML -Code
		
		uppercase($string)
			- Beschreibung: Erster Buchstabe uppercase, bei Wörtern < 3 Zeichen alles uppercase
			- Argumente: 	$string = zu bearbeitender String
			- Return: 		Bearbeiteter String
		
		change_date_format($origDate)
			- Beschreibung: change the date from YYYY-MM-DD to DD.MM.YYYY
			- Argumente:	$origDate = Datum im Format YYYY-MMM-DD
			- Return:		Datum im Format DD.MM.YYYY

*/


// -------- HTML Header -------- 
	function html_header($title){
		// Für den HHC-Server muss "href='/wordpress/wp-content/..." zu "href='/wp-content/..." geändert werden
		global $user_ID;
		get_currentuserinfo();
		if(!('' == $user_ID)){
			$backend_button =  "<a href='/wordpress/wp-admin'><button class='loginout'>Backend</button></a>";
		}

		$loginout = wp_loginout($_SERVER['REQUEST_URI'], false);
		$list_pages = wp_list_pages(array(
			'title_li' => __( '' ),
			'echo' => 0
		));

		$html = "
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset='UTF-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1'>
				<link rel='stylesheet' href='/wordpress/wp-content/themes/twentyfourteen-child/style.css'/>
				<link rel='stylesheet' href='/wordpress/wp-content/themes/twentyfourteen-child/templates/style-suchfunktion.css'/>
				<link rel='stylesheet' href='/wordpress/wp-content/themes/twentyfourteen-child/templates/style-home.css'/>
				<title>$title</title>
			</head>
			<body>
				<div class = 'admin-bar'>
					$backend_button
					<button class='loginout'>$loginout</button>
				</div>
				<nav class='nav panel full-width'>
					$list_pages
				</nav>
				
		";

		return $html;
	}


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
	if (!function_exists('ckeck')) {
		function check($key, $value){
			if(isset($_GET[$key]) && $_GET[$key] == $value){
				return 'checked';
			}
		}
	}	

?>