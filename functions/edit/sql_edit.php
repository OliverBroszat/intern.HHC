<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");



echo "<button type='button' onclick='popup_close(); ajax_post()' style='margin: 1rem auto; display: block;'>Schließen</button>";
echo "<hr>";
echo "<h2>DEBUG-Output</h2>";



// --- DELETE ---
function delete_member($id) {
	global $wpdb;
	$wpdb->delete('Contact', array( 'id' => $id ) );
}


// Lösche alle Einträge mit 'other'
$post_clean = unset_value_in_2d_array($_POST, 'other');

// Wandele POST in einen geordneteren Array um
$data = post_to_array($post_clean);

echo "<b>Operation:</b> ";

// Call SQL-Functions for each Data-Set
if(!empty($_POST['edit'])) {
	// UPDATE OLD => INSERT NEW with OLD ID
	echo "UPDATE ";
	$id = $_POST['edit'];
	
	// DELETE old DATA
	delete_member($id);

	foreach ($data as $table => $content) {
		foreach ($content as $index => $cols) {	
			if ($table != 'edit') {

				if ($table == 'Contact') { 
					$target = 'id';
				}
				else { 
					$target = 'contact';	
				}

				$cols[$target] = $id;	

				$wpdb->insert($table, $cols);
			}
		}
	}
}
elseif(!empty($_POST['delete']) && $table != 'delete') {
	echo "DELETE ";
	$id = $_POST['delete'];
	delete_member($id);
}
else {
	// INSERT NEW
	echo "INSERT ";

	foreach ($data as $table => $content) {
		foreach ($content as $index => $cols) {	
			if ($table != 'edit') {
				// SET ID
				if ($table != 'Contact') 	{ 
					$cols['contact'] = $new_id;
				}

				// SQL-Command
				$wpdb->insert($table, $cols);

				// GET ID
				if ($table == 'Contact') 	{ 
					$new_id = $wpdb->insert_id;
				}
			}
		}
	}
}





// IMAGE

if (!empty($id)) {
	global $wpdb;

	$query = "SELECT id FROM Image WHERE contact_id=%d";
	$query_escaped = $wpdb->prepare($query, $id);
	$attachment_id = $wpdb->get_var($query_escaped);

	// Get image's source path
	$imgsrc_thumb = wp_get_attachment_image_src($attachment_id, $size='')[0];	
}




echo "<br><b>Errors:</b> ";
if (!empty($wpdb->last_error)) {
	echo "<pre>".$wpdb->last_error."</pre>";
}
else {
	echo "NO ERRORS";
}


echo "<br><b>Contact-ID:</b> $id";



echo "<br><b>Image-Operation:</b> ";
if (!empty($_FILES['upload-image']['name'])) {
	// upload new Image
	echo "NEW IMAGE";
}
elseif(!empty($attachment_id)) {
	// use old image
	echo "OLD IMAGE";
}
else {
	// no image/delete old image
	echo "NO IMAGE";
}

echo "<br><b>Image-id:</b> $attachment_id <br>";
echo "<b>Image-src:</b> $imgsrc_thumb <br>";
// echo "Image: <img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /> <br>";

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