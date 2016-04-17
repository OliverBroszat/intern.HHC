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

function getDetailView($number, $dataset) {
	// Get phone numbers
	$phone_html = '';
	$j = 0;
	foreach($dataset['phone'] as $row) {
		//$phone_html .= '<tr><td>'.$row['description'].'</td><td>'.$row['number'].'</td></tr>';
		$phone_html .= '<tr><td>'.$j.'.</td><td>'.$row->number.'</td></tr>';
		$j++;
	}
	
	// Get Addresses
	$address_html = '';
	$j = 0;
	foreach($dataset['address'] as $row) {
		//$address_html .= "<tr><td rowspan='2' style='vertical-align: top;'>".$row['description'] .
		//'</td><td>'.$row['street'].' '.$row['nr'].'</td></tr><tr><td>'.$row['postal'].' '.$row['city'].'</td></tr>';
		$address_html .= "<tr><td rowspan='2' style='vertical-align: top;'>".$j .
		'</td><td>'.$row->street.' '.$row->nr.' '.$row->addr_extra.'</td></tr><tr><td>'.$row->postal.' '.$row->city.'</td></tr>';
		$j++;
	}
	
	// Get Mail Addresses
	$mail_html = '';
	$j = 0;
	foreach($dataset['mail'] as $row) {
		//$mail_html .= '<tr><td>'.$row['description'].'</td><td>'.$row['address'].'</td></tr>';
		$mail_html .= '<tr><td>'.$j.'.</td><td>'.$row->address.'</td></tr>';
		$j++;
	}
	
	return "
<div class='info-list' style='display: block;'>
	<div class='detail-list' style='display: inline-block;'>
		<div class='inner_headline' style='margin-bottom: 15px'>
			Hinterlegte Telefonnummern
		</div>
		<div class='scroll_list' style='max-height: 150px; overflow-y: scroll;'>
			<table>
				$phone_html
			</table>
		</div>
	</div>
	<div class='detail-list' style='display: inline-block;'>
		<div class='inner_headline' style='margin-bottom: 15px'>
			Hinterlegte Adressen
		</div>
		<div class='scroll_list' style='max-height: 150px; overflow-y: scroll;'>
			<table>
				$address_html
			</table>
		</div>
	</div>
	<div class='detail-list' style='display: inline-block;'>
		<div class='inner_headline' style='margin-bottom: 15px'>
			Hinterlegte Telefonnummern
		</div>
		<div class='scroll_list' style='max-height: 150px; overflow-y: scroll;'>
			<table>
				$mail_html
			</table>
		</div>
	</div>
</div>
<div class='dates' style='display: block;'>
	<div class='join' style='display: inline-block;'>
		HHC Beitritt: 01.08.2015
	</div>
	<div class='left' style='display: inline-block; margin-left: 100px;'>
		HHC Austritt: -
	</div>
</div>
<div id='tabs-$number'>
	<ul>
		<li><a href='#tabs-$number-1'>Studiengänge und Praktika</a></li>
		<li><a href='#tabs-$number-2'>Fähigkeiten</a></li>
		<li><a href='#tabs-$number-3'>Notizen</a></li>
	</ul>
	<div id='tabs-$number-1'>
		Kommt noch
	</div>
	<div id='tabs-$number-2'>
		Kommt noch
	</div>
	<div id='tabs-$number-3'>
		Kommt noch
	</div>
</div>".
"<script>".'$(function() {$("'."#tabs-$number".'").tabs();});</script>';
}

function getListEntryHTML($number, $dataset_full) {
	$dataset = $dataset_full['info'];
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
	$button_id = "show_detail_$number";
	return expandableContent($overview, getDetailView($number, $dataset_full['detail']), $button_id);
}
?>