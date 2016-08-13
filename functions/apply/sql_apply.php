<?php

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");


$post = $_POST;

$id = $post['id'];


// Lösche alle Einträge mit 'other'
$post_clean = unset_value_in_2d_array($post, 'other');

// Wandele POST in ein geordnetes Array um
$data = post_to_array($post_clean);


// Include create_sql_insert()-Funktion
require_once(get_template_directory()."/functions/sql/sql-functions.php");

foreach ($data as $key => $table) {
	if ($key != 'File') {
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
				if (condition) {
				}
				$sql .= create_sql_insert($key, $table, $i);
			}
		}
		else {
			$sql .= create_sql_insert($key, $table);
		}
	}
}




// IMAGE
global $wpdb;

$query = "SELECT id FROM Image WHERE contact_id=%d";
$query_escaped = $wpdb->prepare($query, $id);
$attachment_id = $wpdb->get_var($query_escaped);
// Get image's source path
$imgsrc_thumb = wp_get_attachment_image_src($attachment_id, $size='thumbnail')[0];





// DEBUG Output
echo "Contact-ID: $id <br>";
echo "Image: <img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /> <br>";
echo "Image-id: $image_id <br>";
echo "<hr>";

echo "$sql";

echo "<hr>";
arr_to_list($data);

echo "<hr>";
arr_to_list($_FILES);

?>