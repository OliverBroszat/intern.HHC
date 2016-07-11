<?php
<<<<<<< HEAD

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
		 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 

if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 

require_once("$root/wp-load.php");


// Wandele POST in einen geordneteren Array um
$data = array();
foreach ($_POST as $key => $value) {	
	$temp = explode('-', $key);	

	$data[$temp[0]][$temp[1]] = $value;
}


// Funktion, um einen SQL-INSERT Befehl zu erstellen
function create_sql_insert($table, $cols, $i=NULL) {
	$sql = '';
	$sql .= "INSERT INTO $table (";
	foreach ($cols as $col => $value) {
		$sql .= "$col, ";
	}
	$sql .= ')';
	
	$sql .= '<br> VALUES(';
	foreach ($cols as $col => $value) {
		if(is_null($i)){
			$sql .= $value.', ';
		}
		else{
			$sql .= $value[$i].', ';
		}
	}
	$sql .= ')<br><br>';

	return $sql;
}



foreach ($data as $key => $table) {
	
	$is_array = array('bool'=>false,'count'=>0);
	
	foreach ($table as $col => $value) {
		if (is_array($value)) {		
			
			$is_array['bool'] = true;

			$count = count($value);
			if ($count > $is_array['count']) {
				$is_array['count'] = $count;
			}
		}
	}

	if ($is_array['bool']) {			
		for ($i=0; $i < $is_array['count']; $i++) {		
			$sql .= create_sql_insert($key, $table, $i);
		}
	}
	else {
		$sql .= create_sql_insert($key, $table);
	}
}



// DEBUG Output
echo "$sql";

echo "<hr>";
arr_to_list($data);

echo "<hr>";
arr_to_list($_FILES);

=======
	var_dump($_POST);
?>
<br><br>
<?php
	var_dump($_FILES);
>>>>>>> Bewerbungssystem_Alex_Marek
?>