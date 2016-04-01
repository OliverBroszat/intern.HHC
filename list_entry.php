<?php
/**
 * Template Name: ListEntry - Alex
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

require("../../../../wp-load.php");

function getImageHTML($contact_id) {
	/*
		Selects the wordpress attachment ID of the users currently selected
		image from the wordpress media database and returns an HTML image
		tag that references to that image as a thumbnail
	*/
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

function getListEntryHTML($number, $dataset) {
	$image = getImageHTML($dataset['contact_id']);
	return "
	<table class='list_entry'>
	<tr>
		<td class='number' rowspan='4' valign='top'>$number</td>
			<td class='profile' rowspan='3'>$image</td>
			<td class='contact_name' colspan='2'>".$dataset['first_name'].' '.$dataset['last_name']."</td>
			<td class='ressort'>".$dataset['ressort']."</td>
		</tr>
		<tr>
			<td class='active'>".$dataset['active']."</td>
			<td class='status'>".$dataset['status']."</td>
			<td class='text_beitritt'>Beitritt:</td>
			<td>".$dataset['date_join']."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td class='text_beitritt'>Austritt:</td>
			<td>".$dataset['date_leave']."</td>
		</tr>
		<tr>
			<td>DETAIL</td
			><td>MAIL</td>
			<td>EDIT</td>
			<td>DELETE</td>
		</tr>
	</table>";
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/listentry_style.css'/>
		<title>Listeneintrag</title>
	</head>
	<body>
		<?php
// Testlauf
$dataset = array(
	'contact_id' => 135,
	'first_name' => 'Alexander',
	'last_name' => 'Schäfer',
	'ressort' => 'Vorstand',
	'active' => 'Aktiv',
	'status' => 'Ressortleiter',
	'date_join' => 'August 2015',
	'date_leave' => '-'
);
echo getListEntryHTML(4, $dataset);

?>
	</body>
</html>