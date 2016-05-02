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
		"image" => HMTML Code des Profilbilds (wird schon durch die Funktion getImageHTML bereitgestellt)
	)

*/

/*
	Selects the wordpress attachment ID of the users currently selected
	image from the wordpress media database and returns an HTML image
	tag that references to that image as a thumbnail
*/
function getImageHTML($contact_id) {
	// Get attachment ID
	global $wpdb;
	$query = "SELECT id FROM Image WHERE contact_id=%d";
	$query_escaped = $wpdb->prepare($query, $contact_id);
	$attachment_id = $wpdb->get_var($query_escaped);
	// Get image's source path
	$imgsrc_thumb = wp_get_attachment_image_src($attachment_id, $size='thumbnail')[0];
	// Return HTML tag
	if($attachment_id != ""){
		$imgsrc = wp_get_attachment_image_src($attachment_id, $size='')[0];
		$imageHTML = "<a href='$imgsrc' target='_blank'><img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /></a>";
	}
	else {
		$imageHTML = "<img class='profile-picture'>";
	}
	return $imageHTML;
}


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
			'mails' => $detail['mail'],
			'phones' => $detail['phone'],
			'addresses' => $detail['address'],
			'studies' => $detail['study'],
			'image' => getImageHTML($contact_id)
		);		
	
	}	
	
	//if($wpdb->last_error !== '') :
        //    $wpdb->print_error()
        //endif;
        
        $root = realpath($_SERVER["DOCUMENT_ROOT"]);

        
        function error_found(){
            header("Location: ../home.php");
        }
        set_error_handler('error_found');



	return $data;
}

?>
