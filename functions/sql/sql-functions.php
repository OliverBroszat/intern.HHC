<?php 


// INSERT
function create_sql_insert($table, $cols) {
	$sql = '';
	$sql .= "INSERT INTO $table (";
	$first = true;
	foreach ($cols as $col => $value) {
		 if (!$first) {
			$sql .= ", ";
		}
		$first = false;
		$sql .= "$col";
	}
	$sql .= ')';
	
	$sql .= '<br> VALUES (';
	$first = true;
	foreach ($cols as $col => $value) {
		if (!$first) {
			$sql .= ", ";
		}
		$first = false;
		
		$sql .= "'$value'";
	}
	$sql .= ');';

	return $sql;
}



// UPDATE
function create_sql_update($table, $cols, $target, $id) {
	/*Target
		UPDATE Contact
		SET prefix='Herr', first_name='Daniel', last_name='000Högel', birth_date='1996-01-11'
		WHERE id='134';

	*/

	$sql .= "UPDATE $table ";
	$sql .= "<br>";
	$sql .= "SET ";

	$first = true;
	foreach ($cols as $key => $value) {
		if (!$first) {
			$sql .= ", ";
		}
		$first = false;

		$sql .= "$key='$value'";
	}
	$sql .= "<br>";

	$sql .= "WHERE $target='$id';";

	return $sql;

}



 ?>