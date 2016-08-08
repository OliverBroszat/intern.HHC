<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

$root = get_template_directory();
require_once("$root/functions/edit/sql_functions.php");

echo "<button type='button' onclick='popup_close(); ajax_post()' style='margin: 1rem auto; display: block;'>Schließen</button>";
echo "<hr>";
echo "<h2>DEBUG-Output</h2>";


// --- DELETE ---
// function delete_member($id) {
// 	global $wpdb;
// 	$wpdb->delete('Contact', array( 'id' => $id ) );
// }

$base = new BaseDataController();
$contact = new ContactDataController(null, $base);

function delete_member($id) {
	global $contact;
	$contact->deleteSingleContactByID($id);
}


// Lösche alle Einträge mit 'other'
$post_clean = unset_value_in_2d_array($_POST, 'other');
// Wandele POST in einen geordneteren Array um
$data = post_to_array($post_clean);
// get CRUD infos
$crud = $data['crud'][0];

arr_to_list($data);
echo "<b>Operation:</b> ";

$base = new BaseDataController();
$contact = new ContactDataController(null, $base);
$member = new MemberDataController(null, $contact);

$dataObject = array();
foreach ($data as $table => $tables) {
	$rows = array();
	foreach ($tables as $index => $values) {
		//$dataObject[$table][$index] = new DatabaseRow((object) $values);
		$rows[$index] = new DatabaseRow((object) $values);
	}
	$dataObject[$table] = $rows;
};

$newProfile = new ContactProfile(
	$dataObject['Contact'][0],
	$dataObject['Address'],
	$dataObject['Mail'],
	$dataObject['Phone'],
	$dataObject['Study']
);

$newMember = new MemberProfile(
	$dataObject['Member'][0],
	$newProfile
);

// Call SQL-Functions for each Data-Set
if($crud['mode'] == 'edit' && !empty($crud['id'])) {
	
	// UPDATE OLD => DELETE OLD and INSERT NEW with OLD ID
	echo "UPDATE ";
	$id = $crud['id'];

	// Get Image ID
	global $wpdb;
	$query = "SELECT id FROM Image WHERE contact_id=%d";
	$query_escaped = $wpdb->prepare($query, $id);
	$attachment_id = $base->tryToGetSingleRowByQuery($query_escaped)->getValueForKey('id');

	$member->updateSingleMemberByProfileWithID($id, $newMember);
	// CONTACT NEU ERSTELLEN
	
	// // delete old data
	// $contact->deleteSingleContactByID($id);

	// // insert new data
	// foreach ($data as $table => $content) {
	// 	foreach ($content as $index => $cols) {	
	// 		if ($table == 'Contact') { 
	// 			$target = 'id';
	// 		}
	// 		else { 
	// 			$target = 'contact';	
	// 		}

	// 		$cols[$target] = $id;	

	// 		$wpdb->insert($table, $cols);
	// 	}
	// }
}
elseif($crud['mode'] == 'delete') {
	
	// DELETE
	echo "DELETE ";
	$id = $crud['id'];
	$contact->deleteSingleContactByID($id);
}
else {

	// INSERT NEW
	echo "INSERT ";

	$member->createSingleMemberByProfile($newMember);

}





echo "<br><b>Contact-ID:</b> $id";


echo "<br><b>Image-Operation:</b> ";
if (!empty($_FILES['upload_image']['name'])) {
	// upload new Image
	echo "NEW IMAGE";

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	$attachment_id = media_handle_upload('upload_image',0);
			
	if (is_wp_error($attachment_id)) {
		echo "<br>ERROR (Image Upload)<br>";
	} else {
		// The image was uploaded successfully!
		$base->tryToInsertData('Image',
			array(
				'id' => $attachment_id,
				'contact_id' => $id
			)
		);
	}
}
elseif(!empty($attachment_id) && $crud['delete_image'] == 'false') {
	// use old image
	echo "OLD IMAGE";
	$base->tryToInsertData('Image',
		array(
			'id' => $attachment_id,
			'contact_id' => $id
		)
	);
}
else {
	// no image/delete old image
	echo "NO IMAGE";
}

// Debug Output for Image
if (!empty($attachment_id)) {
	echo "<br><b>Image-id:</b> $attachment_id <br>";
	echo "<b>Image-src:</b> $imgsrc_thumb <br>";
	// echo "OLD Image: <img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /> <br>";
}

//Debug Output continue
echo "<br><b>Errors:</b> ";
if (!empty($wpdb->last_error)) {
	echo "<pre>".$wpdb->last_error."</pre>";
}
else {
	echo "NO ERRORS";
}
echo "<hr>";

echo "<h3>Data</h3>";
arr_to_list($data);
echo "<hr>";

echo "<h3>Files</h3>";
arr_to_list($_FILES);
echo "<hr>";

echo "<h3>Queries</h3>";
echo "<pre>";
print_r($wpdb->queries);
echo "</pre>";

echo "<hr>";

?>