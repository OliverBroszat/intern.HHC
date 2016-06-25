<?php
/* 
	search_suggestions.php 

	Suchvorschläge beim eintippen der Suchworte
*/


$localhost = array(
    '127.0.0.1',
    '::1'
);

$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
    if (strpos($root, '\\')){ $root .= "/wordpress"; }
}
require_once("$root/wp-config.php");


$search_text = explode(" ", trim($_POST["search_text"]));


// ---------- SQL Abfrage ---------- 

$query = "
		SELECT INSTR(res.first_name, '%1\$s') AS erg, res.first_name, last_name, id
		FROM (
			SELECT * 
			FROM (
				SELECT first_name, last_name, id FROM Contact 
				UNION 
				SELECT last_name, first_name, last_name FROM Contact
				UNION 
				SELECT city, NULL, NULL FROM Address
				UNION 
				SELECT school, NULL, NULL FROM Study
				UNION 
				SELECT course, NULL, NULL FROM Study
				UNION 
				SELECT number, NULL, NULL FROM Phone
			) AS text
			WHERE 
				text.first_name LIKE '%%%1\$s%%'
		) AS res 
		ORDER BY erg, res.first_name;
  	";
$query_escaped = $wpdb->prepare($query, $search_text[0]);
$result = $wpdb->get_results( $query_escaped );


// ---------- Ergebnis der Abfrage auswerten: "Vorname Nachname", "Nachname, Vorname", "Value" ---------- 

$suggest = array();
foreach ($result as $index) {
	if ($index->erg < 16) {
		if (!empty($index->last_name)) {
			if (is_numeric($index->id)) {
				$value = $index->first_name." ".$index->last_name;
			}
			else{
				$value = $index->first_name.", ".$index->last_name;
			}
		}
		else{
			$value = $index->first_name;
		}
		array_push($suggest, $value);
	}
}


// ---------- Suchwort in Suchvorschlag markieren und ausgeben ---------- 

foreach ($suggest as $value) {

	$pos = stripos($value, $search_text[0]);
	$pos_end = $pos + strlen($search_text[0]);

	$value = substr_replace($value, '</b>', $pos_end, 0);
	$value = substr_replace($value, '<b>', $pos, 0);

	echo "<div class = 'suggestion' onclick='add_to_search_box(this.innerHTML)'>".strip_tags($value)."</div>";

}


?>
