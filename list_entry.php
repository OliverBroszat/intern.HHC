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

?>