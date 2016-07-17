<?php  

if (!function_exists('getRoot')) {
	function getRoot() {
		$localhost = array( '127.0.0.1', '::1' ); 
		 
		$root = realpath($_SERVER["DOCUMENT_ROOT"]); 

		if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
		    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
		} 

		require_once("$root/wp-load.php");

		$root = get_template_directory();
		
		return $root;
	}
}

	
// Funktion zum Debuggen. Gibt einen Array in einer Key-Value-TaBeLle (kvtbl) aus.
// Angepasst an den Aufbau des $_POST Objekts:
if (!function_exists('kvtbl')) {
	function kvtbl($post){
		$html = '';

		$html .= "
			<style>
				.kvtbl{
					margin: 0 auto;
					font-family: verdana, sans-serif;
					border:none;
					border-collapse: collapse;
				}
				.kvtbl tr{
					background-color: #f9f9f9;
				}
				.kvtbl tr:nth-child(even){
					background-color: #eee;
				}
				.kvtbl > tbody > tr:hover{
					background-color: #cde;
				}
				.kvtbl td, .kvtbl th{
					border: 1px solid #000;
					padding: 0.25rem;
				}
				.kvtbl th{
					background-color: #ccc;
				}
			</style>
		";

		$html .= "
			<table class='kvtbl'>
				<tr>
					<th> Key</th>
					<th>Value</th>
				</tr>
		";
		foreach ($post as $key => $value) {
			$html .= "
				<tr>
					<td>
						<i>$key</i>
					</td>
					<td>
			";
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$html .= "[$k] $v <br>";
				}
			} else {
				$html .= $value;
			}
			
			$html .= "	
					</td>
				</tr>
			";
		}
		
		$html .= "</table>";

		echo $html;
	}
}


// Funktion zum Debuggen. Gibt einen Array in einer verschachtelten Tabelle aus.
if (!function_exists('arr_to_tbl')) {
	function arr_to_tbl($array){
		echo "
			<style type='text/css'>
				.arr_to_tbl {
				    max-width: 720px;
				    overflow-x: auto;
				    margin: 1rem auto;
				}

				.arr_to_tbl table {
					border-collapse: collapse;
					border: 1px solid #aaa;
					padding: 0;
					font-family: sans-serif;
					color: #000;
				}

				.arr_to_tbl td, .arr_to_tbl th {
					border: 1px solid #aaa;
					padding: 4px;
				}

				.arr_to_tbl th {
					background-color: #ccc;
				}

				.arr_to_tbl td:first-child {
					background-color: #eee;
				}
				.arr_to_tbl td:nth-child(2) {
					background-color: #f9f9f9;
				}
			</style>

			<div class='arr_to_tbl'>
		";

		function create_table($array) {	
			echo "<table>";
			// echo "
			// 	<tr>
			// 		<th>Key</th>
			// 		<th>Type</th>
			// 		<th>Value</th>
			// 	</tr>
			// ";
			foreach ($array as $key => $value) {
				$type = gettype($value);

				echo "<tr>";
				if (is_array($value) || is_object($value)) {
					echo "<td>$key</td>";
					echo "<td>$type</td>";
					echo "<td>";
					echo create_table($value);
					echo "</td>";
				}
				else {
					echo "<td>$key</td>";
					echo "<td>$type</td>";
					echo "<td>" . var_export($value, true) . "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}

		create_table($array);

		echo "</div>";
	}
}


// Funktion zum Debuggen. Gibt einen Array in einer verschachtelten Liste aus.
if (!function_exists('arr_to_list')) {
	function arr_to_list($array) {
		echo "
			<style type='text/css'>	
				.arr_to_list {
					max-width: 720px;
					overflow-x: auto;
					margin: 1rem auto;
				}

				.arr_to_list ul {
					font-family: sans-serif;
					background-color: rgba(0,25,75,0.1);
					width: auto;
					display: block;
					list-style:none;
			    	white-space: nowrap;
			    	color: #000;
			    	padding: 4px;
	    			margin-left: 40px;
				}
				.arr_to_list > ul {
					margin: 0;
				}

				.arr_to_list li {
					display: block;
					width: auto;
					padding: 2px;
				}
			</style>
		";

		echo "<div class='arr_to_list'>";
		if (!function_exists('create_list')) {
			function create_list($array) {	
				echo "<ul>";
			
				foreach ($array as $key => $value) {
					$type = gettype($value);

					if (is_array($value) || is_object($value)) {
						echo "<li>[<b>".$key."</b>] (".$type.")";
						echo create_list($value);
						echo "</li>";
					}
					else {
						echo "<li>[<b>$key</b>] ($type)  ->  <i>".var_export($value, true)."</i></li>";
					}
				}
				echo "</ul>";
			}
		}
		create_list($array);
		echo "</div>";
	}
}



// Findet in POST den Key und den Index zu einer Value (aus einem HTML-Array)
function search_in_2d_array($array, $needle) {
	$result = array();
	foreach ($array as $key => $value) {
	    if (is_array($value)) {
	    	foreach ($value as $index => $val) {
	    		if ($val == $needle) {
	    			array_push($result, array('key' => $key, 'index' => $index));
	    		}
	    	}
	    }
	}
	return $result;
}



// Lösche aus aus dem HTML-Array alle die gesuchten Einträge und re-indiziere das HTML-Array
function unset_value_in_2d_array($array, $needle) {

	$result = search_in_2d_array($array, $needle);

	foreach ($result as $result) {
		$key = $result['key'];
		$index = $result['index'];

		unset($array[$key][$index]);
		$array[$key] = array_values($array[$key]);

	}

	return $array;
}



// Wandele POST in ein geordnetes Array um
function post_to_array($post) {
	$data = array();
	foreach ($post as $key => $value) {	
		if ($key != 'id') {
			$temp = explode('-', $key);	
			$count = count($value);

			for ($i=0; $i < $count; $i++) {
				if (is_array($value)) {
					$data[$temp[0]][$i][$temp[1]] = $value[$i];
				} 
				else {
					$data[$temp[0]][$i][$temp[1]] = $value;
				}
			}
		}
	}
	return $data;
}


?>