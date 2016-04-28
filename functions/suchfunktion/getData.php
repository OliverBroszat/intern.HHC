<?php 
/* 
	getData.php 

	Enthält die Funktion getData($queries), welche das Query-Array annimmt und die Anfragen entsprechend durchführt. Zurückgegeben wird ein Array folgender Struktur:

	Contact_ID =>	array(
		"info" => Ergebnis der "contact_search" Query, FÜR DIESEN CONTACT,
		"mails" => Ergebnisse der "mail" Query FÜR DIESEN CONTACT,
		"phones" => ...
		"addresses" => ...,
		"studies" => ...
	)

*/


// ---------- get Details----------
function getDetail($contact_id) {
	/*
		Fetch detail information from database, such as phone
		number, mail addresses and other stuff...
	*/
	global $wpdb;
	$detail_array = array();
	$detail_array['phone'] = $wpdb->get_results("
		SELECT number FROM Phone WHERE contact=$contact_id;
	");
	$detail_array['mail'] = $wpdb->get_results("
		SELECT address FROM Mail WHERE contact=$contact_id;
	");
	$detail_array['address'] = $wpdb->get_results("
		SELECT * FROM Address WHERE contact=$contact_id;
	");
	$detail_array['study'] = $wpdb->get_results("
		SELECT * FROM Study WHERE contact=$contact_id;
	");
	return $detail_array;
}


/* 
----------------------------------------
---------- Hauptfunktion ---------- 
----------------------------------------
*/

function getData($queries){

	global $wpdb;

	// vorerst Beschränkung auf die Hauptsucheanfrage
	$query_contact_search = $queries['contact_search'];

	// $wpdb->prepare wird noch nicht verwendet
	$results = $wpdb->get_results($query_contact_search);

	
	$data = array();

	foreach ($results as $row) {

		$contact_id = $row->id;

		$detail = getDetail($contact_id);

		$data[$contact_id] = array(
			'info' => $row,
			'detail' => $detail,
			'mails' => '',
			'phones' => '',
			'addresses' => '',
			'studies' => ''
		);		
	
	}	

	return $data;
}

?>
