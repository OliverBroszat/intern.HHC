<?php

//require("../../../../wp-load.php");

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

function expandableContent($fix_html, $slide_html, $click_id) {
	/*
		Returns HTML code of two div tags. the first tag wil include the HTML code
		from $fix_html, the other will include the code from $slide_html. The second
		div will be invisible unless the element with id $click_id is clicked.
		In this case the second div will expand with a slide animation. This
		action can be reversed by clicking again on the action element.
		
		NOTE: This code only works properly if jquery is included in the document
		NOTE: This code only works properly if $click_id is unique in the document
		
	*/
	return "
		<div id='fix_content_$click_id'>
			$fix_html
		</div>
		<div id='slide_content_$click_id' style='display: none;overflow: hidden; position: relative;'>
			$slide_html
		</div>
		<script>
			$('#$click_id').click(function(){
        		$('#slide_content_$click_id').slideToggle(300);
    		});
		</script>";
}

function getListEntryHTML($number, $dataset) {
	$image = getImageHTML($dataset->id);
	$overview = "
	<table class='list_entry'>
		<tr>
			<td class='number' rowspan='4' valign='top' width='5%'>$number</td>
			<td class='profile' rowspan='3' width='19%'>$image</td>
			<td class='contact_name' colspan='2' width='38%'><b>".$dataset->first_name.' '.$dataset->last_name."</b></td>
			<td class='ressort' width='19%'><b>".$dataset->name."</b></td>
			<td></td>
		</tr>
		<tr>
			<td class='active'>".$dataset->active."</td>
			<td class='status'>".$dataset->position."</td>
			<td class='text_beitritt'>Beitritt:</td>
			<td>".$dataset->joined."</td>
		</tr>
		<tr>
			<td colspan='2'></td>
			<td class='text_beitritt'>Austritt:</td>
			<td>".$dataset->left."</td>
		</tr>
		<tr>
			<td><button id='show_detail_$number' class='full-width' type='button'>DETAIL</button></td>
			<td><button class='full-width'>MAIL</button></td>
			<td><button class='full-width'>EDIT</button></td>
			<td><button class='full-width'>DELETE</button></td>
			<td></td>
		</tr>
	</table>";
	$detail_view = "<h1>DETAIL</h1>";
	$button_id = "show_detail_$number";
	return expandableContent($overview, $detail_view, $button_id);
}
?>