<?php  

if (!function_exists('getRoot')) {
	function getRoot() {
		$root = realpath($_SERVER["DOCUMENT_ROOT"]);  

		if (strpos($root, '\\')){  
		  // localhost  
		  $root .= "/wordpress";  
		}  

		require_once("$root/wp-load.php");

		$root = get_template_directory();
		return $root;
	}
}

	
	// Funktion zum Debuggen. Gibt einen Array in einer Key-Value-TaBeLle (kvtbl) aus.
	// Angepasst an den Aufbau des $_POST Objekts:
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

	
	// $arr = array(
	// 	'Hallo',
	// 	'Welt',
	// 	array(
	// 		'id' => 123,
	// 		'name' => 'Daniel'
	// 	),
	// 	array(
	// 		array(
	// 			'Baum',
	// 			'Strauch',
	// 			'Honig'
	// 		),
	// 		array(
	// 			'Welt',
	// 			'Haus'
	// 		),
	// 		'Farben' => array(
	// 			'blau',
	// 			'gelb',
	// 			'grün',
	// 			'shadings' => array(
	// 				'hell',
	// 				'dunkel',
	// 				'mittel'
	// 			)
	// 		)
	// 	),
	// 	'Tiere' => array(
	// 		'Vögel' => array(
	// 			'Specht',
	// 			'Adler'
	// 		),
	// 		'Säuger' => array(
	// 			'Schweine',
	// 			'Hunde',
	// 			'Kühe'
	// 		),
	// 		'Schlange',
	// 		'Affe',
	// 		'Mensch'
	// 	),
	// 	'Schlusswort' => 'Good by!'
	// );



function arr_to_tbl($array){
	echo "
		<style type='text/css'>
			table.arr_to_tbl {
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
	";

	function create_table($array) {	
		echo "<table class='arr_to_tbl'>";
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
}


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

	function create_list($array) {	
		echo "<ul>";

		foreach ($array as $key => $value) {
			$type = gettype($value);

			if (is_array($value) || is_object($value)) {
				echo "<li>
						[<b>".$key."</b>] (".$type.")
						
					</li>
					<li>
						".create_list($value)."
					</li>
					";
			}
			else {
				echo "<li>[<b>$key</b>] ($type)  ->  <i>".var_export($value, true)."</i></li>";
			}
		}
		echo "</ul>";
	}

	create_list($array);

	echo "</div>";

}


?>