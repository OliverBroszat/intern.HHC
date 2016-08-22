<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

echo "<button type='button' class='popup-close' style='margin: 1rem auto; display: block;'>Schließen</button>";
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

$base = new BaseDataController();
$contact = new ContactDataController(null, $base);
$member = new MemberDataController(null, $contact);

$dataObject = array();
foreach ($data as $table_name => $tables) {
	foreach ($tables as $index => $values) {
		$dataObject[$table_name][$index] = new DatabaseRow((object) $values);
	}
};

// Workarround for missing datastructures if empty
$tablesNames = array('Address', 'Mail', 'Phone', 'Study');
foreach ($tablesNames as $tableName) {
	if (!isset($dataObject[$tableName])) {
		echo "<br>$tableName fehlt, also setze leeres Array<br>";
		$dataObject[$tableName] = array();
	}
}

$newProfile = new ContactProfile(
	$dataObject['Contact'][0],
	$dataObject['Address'],
	$dataObject['Mail'],
	$dataObject['Phone'],
	$dataObject['Study']
);

var_dump($newProfile);

$newMember = new MemberProfile(
	$dataObject['Member'][0],
	$newProfile
);

// Get Image ID
global $wpdb;
$query = "SELECT image FROM Contact WHERE id=%d";
$query_escaped = $wpdb->prepare($query, $id);
try {
	$attachment_id = $base->selectSingleRowByQuery($query_escaped)->getValueForKey('image');
	echo 'THE ID: ';
	var_dump($attachment_id);
	echo '<br><br>';
}
catch (LengthException $e) {
}

echo "<br><b>Image-Operation:</b> ";
if ($crud['delete_image'] == 'false') {
	if (!empty($_FILES['upload_image']['name'])) {
		// upload new Image
		echo "NEW IMAGE";

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
		$attachment_id = media_handle_upload('upload_image',0);
				
		if (is_wp_error($attachment_id)) {
			echo "<br>ERROR (Image Upload)<br>";
			$attachment_id = 'error';
		} else {
			// The image was uploaded successfully!
			$newProfile->contactDatabaseRow->setValueForKey('image', $attachment_id);
		}
	}
	elseif(!empty($attachment_id)) {
		// use old image
		echo "OLD IMAGE";
		$newProfile->contactDatabaseRow->setValueForKey('image', $attachment_id);
	}
	else {
		// no image/delete old image
		echo "NO IMAGE";
	}
}
else {
	echo 'DELETE IMAGE';
	$newProfile->contactDatabaseRow->setValueForKey('image', null);
}

// Debug Output for Image
if (!empty($attachment_id)) {
	echo "<br><b>Image-id:</b> $attachment_id <br>";
	echo "<b>Image-src:</b> $imgsrc_thumb <br>";
	// echo "OLD Image: <img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /> <br>";
}

echo "<b>Operation:</b> ";
// Call SQL-Functions for each Data-Set
if($crud['mode'] == 'edit' && !empty($crud['id'])) {
	
	// UPDATE OLD => DELETE OLD and INSERT NEW with OLD ID
	echo "UPDATE ";
	$id = $crud['id'];

	
	$member->updateSingleMemberProfile($newMember);

}
elseif($crud['mode'] == 'delete') {
	
	// DELETE
	echo "DELETE ";
	$id = $crud['id'];
	$member->deleteSingleMemberByID($id);
}
else {

	// INSERT NEW
	echo "INSERT ";

	$member->createSingleMemberByProfile($newMember);

}





echo "<br><b>Contact-ID:</b> $id";




//Debug Output continue
echo "<br><b>Errors:</b> ";
if (!empty($wpdb->last_error)) {
	echo "<pre>".$wpdb->last_error."</pre>";
}
else {
	echo "NO ERRORS";
}
echo "<hr>";

echo "<h3>DataObject</h3>";
arr_to_list($dataObject);
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