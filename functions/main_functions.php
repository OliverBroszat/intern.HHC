<?php 
/*
	Hier werden verschiedene kleinere Funktionen ausgelagert.
*/


// Server:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


// -------- Lade andere PHP Dateien --------
function requires(){	
	global $root;
	require_once("$root/wp-config.php");
	require_once("$root/wp-load.php");
}

requires();


// -------- HTML Header -------- 
function html_header($title){
	global $root;
	// Für den HHC-Server muss "href='/wordpress/wp-content/..." zu "href='/wp-content/..." geändert werden
	global $user_ID;
	get_currentuserinfo();
	if(!('' == $user_ID)){
		$backend_button =  "<a href='/wp-admin'><button class='loginout'>Backend</button></a>";
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

			<script src='/wp-content/themes/twentyfourteen-child/import/1.12.0.jquery.min.js'></script>
			<script src='/wp-content/themes/twentyfourteen-child/import/1.11.4.jquery-ui.min.js'></script>
			<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/import/1.11.4.jquery-ui.min.css'>

			<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
			<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/styles/style_suche.css'/>
			<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/styles/style_home.css'/>
			<script src='/wp-content/themes/twentyfourteen-child/js/search.js'></script>

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