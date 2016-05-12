<?php 
/* 
	createHTML.php 

	Nimmt die fertigen Daten von postProcess entgegen und generiert HMTL Code, welcher dann per echo ausgegeben werden kann.
*/


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
		<div id='slide_content_$click_id' class='detail-content' style='display: none;overflow: hidden; position: relative;'>
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
	if (empty($dataset['phones'])) {
		$phone_html = '<i>Kein Eintrag</i>';
	}
	else {
		foreach($dataset['phones'] as $row) {
			$phone_html .= "<tr><td width='10%'>".$row->description."</td><td>".$row->number."</td></tr>";
		}
	}
	
	// Get Addresses
	$address_html = '';
	if (empty($dataset['addresses'])) {
		$address_html = '<i>Kein Eintrag</i>';
	}
	else {
		foreach($dataset['addresses'] as $row) {
			$address_html .= "<tr><td width='10%'>".$row->description .
			"</td><td>".$row->street." ".$row->number." ".$row->addr_extra."<br>".$row->postal." ".$row->city."</td></tr>";
		}
	}
	
	// Get Mail Addresses
	$mail_html = '';
	if (empty($dataset['mails'])) {
		$mail_html = '<i>Kein Eintrag</i>';
	}
	else {
		foreach($dataset['mails'] as $row) {
			//$mail_html .= '<tr><td>'.$row['description'].'</td><td>'.$row['address'].'</td></tr>';
			$mail_html .= "<tr><td width='10%'>".$row->description
			."</td><td><a href='mailto:".$row->address."'>".$row->address."</a></td></tr>";
		}
	}

	// Get Studies
	$study_table = '<table><tr><td colspan="2" style="text-align: center;"><b>Studiengänge</b></td></tr>';
	//foreach($dataset['studies'] as $row) {
	foreach($dataset['detail']['study'] as $row) {
		$tr = '<tr><td>';
		 $status = $row->status;
		 switch($status) {
		 	case 'active':
		 		$tr .= '<span class="study_status_active">Aktuell</span>';
		 		break;
		 	case 'cancelled':
		 		$tr .= '<span class="study_status_cancelled">Abgebrochen</span>';
		 		break;
		 	case 'done':
		 		$tr .= '<span class="study_status_done">Abgeschlossen</span>';
		 		break;
		 }
		 $tr .= '</td><td><b>'.$row->course.'</b></td></tr>';
		 $tr .= '<tr><td colspan="2">'.$row->school.'</td>';
		 $study_table .= $tr;
	}
	$study_table .= '</table>';

	$internships_table = '<span><i>Praktika werden noch nicht unterstützt</i></span>';

	$html_studies_internship = '<div style="display: inline-block; width: 50%;">'.$study_table.'</div>';
	$html_studies_internship .= '<div style="display: inline-block; width: 50%; vertical-align: top; text-align: center;">'.$internships_table.'</div>';

	// Get notes
	$html_notes = $dataset['info']->comment;
	if(empty($html_notes)) {
		$html_notes = "<b>Keine Notizen vorhanden</b>";
	} else {
		$html_notes = "<b>Notizen</b><br><pre>".$html_notes."</pre>";
	}
	
	return "
<div class='info-list'>
	<div class='data-block'>
		<div class='data-set'>
			<span class='data-set-title'>Hinterlegte Adressen</span>
			<div class='scroll-list'>
				<table>$address_html</table>
			</div>
		</div>
		<div class='data-set'>
			<span class='data-set-title'>E-Mail</span>
			<div class='scroll-list'>
				<table>$mail_html</table>
			</div>
		</div>
		<div class='data-set'>
			<span class='data-set-title'>Telefonnummern</span>
			<div class='scroll-list'>
				<table>$phone_html</table>
			</div>
		</div>
	</div>
	<div class='data-block'>
		<div class='data-set'>
			<span class='data-set-title'>Beitritt</span>
			<div class='scroll-list'>
				<table>
					<tr>
						<td width='10%'>Beitritt</td>
						<td>".$dataset['info']->joined."</td>
					</tr>
					<tr>
						<td width='10%'>Austritt</td>
						<td>".$dataset['info']->left."</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>


<div id='tabs-$number'>
	<ul>
		<li><a href='#tabs-$number-1'>Studiengänge und Praktika</a></li>
		<li><a href='#tabs-$number-2'>Fähigkeiten</a></li>
		<li><a href='#tabs-$number-3'>Notizen</a></li>
	</ul>
	<div id='tabs-$number-1'>".
		$html_studies_internship
	."</div>
	<div id='tabs-$number-2'>
		Fähigkeiten <i>(wird noch nicht unterstützt...)</i>
	</div>
	<div id='tabs-$number-3'>".
		$html_notes
	."</div>
</div>".
"<script>".'$(function() {$("'."#tabs-$number".'").tabs();});</script>';
}

function getListEntryHTML($number, $dataset_full) {
	$dataset = $dataset_full['info'];
	$image = $dataset_full['image'];
	$overview = "
	<table class='list_entry'>
		<tr>
			<td class='number' rowspan='4' style='vertical-align: top;' width='5%'>$number</td>
			<td class='profile' rowspan='4' width='19%'>$image</td>
			<td class='contact_name' width='38%'><b>".$dataset->first_name.' '.$dataset->last_name."</b></td>
			<td align='right'><div class='status ".$dataset->active."'></div></td>
		</tr>
		<tr>
			<td class='status'> Position: ".$dataset->position."</td>
		</tr>
		<tr>
			<td class='ressort' width='19%'>Ressort: ".$dataset->name."</td>
		</tr>
		<tr>
			<td><button id='show_detail_$number' class='full-width' type='button'>DETAIL</button></td>
		</tr>
	</table>";
	$button_id = "show_detail_$number";
	return expandableContent($overview, getDetailView($number, $dataset_full), $button_id);
}

function createHTML($final){
	
	$entries = '';
	$number = 1;
	foreach ($final as $row) {

		// echo "<hr>Row:<br>";
		// var_dump($row);
		// echo "<br><br>";

		$entries .= "<tr><td>".getListEntryHTML($number, $row)."</td></tr>";
		$number ++;
	}
	
	$html = "
		<table class='liste search_results'>
			$entries
		</table>
	";

	return $html;
}

?>