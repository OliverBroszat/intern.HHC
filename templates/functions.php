<?php 
/*
	Hier werden verschiedene kleinere Funktionen ausgelagert.
*/


// -------- Lade andere PHP Dateien --------
function requires(){	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]).'/wordpress';

	require_once("$root/wp-config.php");
	require_once("$root/wp-load.php");
	require_once("$root/wp-content/themes/twentyfourteen-child/templates/list_entry.php");
	require_once("$root/wp-content/themes/twentyfourteen-child/templates/member_search.php");
}

requires();


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
			<link rel='stylesheet' href='/wordpress/wp-content/themes/twentyfourteen-child/style_suche.css'/>
			<link rel='stylesheet' href='/wordpress/wp-content/themes/twentyfourteen-child/style_home.css'/>
			<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
			<script src='/wordpress/wp-content/themes/twentyfourteen-child/js/search.js'></script>
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
		global $filter;

		if (!empty($filter[$key])) {	
			$find = array_search($value, $filter[$key]);
			
			if ($find !== False) {
				return " checked ";	
			}
		}
	}
}	

?>