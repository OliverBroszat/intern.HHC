<?php 
/* 
	createHTML.php 

	Nimmt die fertigen Daten von postProcess entgegen und generiert HMTL Code, welcher dann per echo ausgegeben werden kann.
*/


function expandableContent($fix_html, $slide_html) {
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
		<div id='fix_content' class='fix-content'>
			$fix_html
			$slide_html
		</div>
		";
}

function getDetailView($number, $dataset) {

	// Get phone numbers
	$phone_html = '';
	if (empty($dataset['phones'])) {
		$phone_html = '<i>Kein Eintrag</i>';
	}
	else {
		foreach($dataset['phones'] as $row) {
			$phone_html .= "<tr><td width='10%'><input type='text' value='".$row->description."'></td><td><input type='text' value='".$row->number."'></td><td><button>-</button></td></tr>";
		}
	}
	
	// Get Addresses
	$address_html = '';
	if (empty($dataset['addresses'])) {
		$address_html = '<i>Kein Eintrag</i>';
	}
	else {
		foreach($dataset['addresses'] as $row) {
			$address_html .= "<tr><td width='10%'><input type='text' value='".$row->description .
			"'></td><td>".$row->street." ".$row->number." ".$row->addr_extra."<br>".$row->postal." ".$row->city."</td></tr>";
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
			$mail_html .= "<tr><td width='10%'><input type='text' value='".$row->description
			."'></td><td><input type='email' value='".$row->address."'></td><td><button>-</button></td></tr>";
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
			<span class='data-set-title'>Hinterlegte Adressen</span><button>+</button>
			<div class=''>
				<table>
					<tr>
						<th>Beschriftung</th>
						<th>Daten</th>
						<th></th>
					</tr>	
					$address_html
				</table>
			</div>
		</div>
		<div class='data-set'>
			<span class='data-set-title'>E-Mail</span><button>+</button>
			<div class=''>
				<table>
					<tr>
						<th>Beschriftung</th>
						<th>Daten</th>
						<th></th>
					</tr>	
					$mail_html
				</table>
			</div>
		</div>
		<div class='data-set'>
			<span class='data-set-title'>Telefonnummern</span><button>+</button>
			<div class=''>
				<table>
					<tr>
						<th>Beschriftung</th>
						<th>Daten</th>
						<th></th>
					</tr>
					$phone_html
				</table>
			</div>
		</div>
	</div>
	<div class='data-block'>
		<div class='data-set'>
			<span class='data-set-title'>HHC-Mitgliedschaft</span>
			<div class=''>
				<table>
					<tr>
						<td width='10%'>Beitritt</td>
						<td><input type='date' value='".$dataset['info']->joined."'></td>
						<td width='10%'>Austritt</td>
						<td><input type='date' value='".$dataset['info']->left."'></td>
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

function getListEntryHTML($dataset_full) {
	$dataset = $dataset_full['info'];
	$image = $dataset_full['image'];

	if ($dataset->active == 'Aktiv') {
		$status =  '
			<option>Aktiv</option>
			<option>Passiv</option>
		';
	} else{
		$status = '
			<option>Passiv</option>
			<option>Aktiv</option>
		';
	}

	$overview = "
	<table>
		<tr>
			<td class='profile' rowspan='6' width='19% valign='top'>
				$image
				<button class='full-width'>Hochladen</button>
				<button class='full-width'>Löschen</button>
			</td>
			<td width='19%'>Vorname: </td>
			<td class='contact_name' ><input type='text' value='".$dataset->first_name."'></td>
		</tr>
		<tr>
			<td>Nachname: </td>
			<td class='contact_name' ><input type='text' value='".$dataset->last_name."'></td>
		</tr>
		<tr>
			<td>Geburtsdatum: </td>
			<td class='birth_date' ><input type='date' value='".$dataset->birth_date."'></td>
		</tr>
		<tr>
			<td>Status: </td>
			<td class= 'active'><select>$status</select></td>
		</tr>
		<tr>
			<td>Position: </td>
			<td class='status'><input type='text' value='".$dataset->position."'></td>
		</tr>
		<tr>	
			<td>Ressort: </td>
			<td class='ressort'><input type='text' value='".$dataset->name."'></td>
		</tr>
	</table>";
	return expandableContent($overview, getDetailView($number, $dataset_full));
}

function createHTML($final){
	
	// var_dump($final);

	if (empty($final)) {
		$info = new stdClass;
		$info->id = '';
		$info->prefix = '';
		$info->first_name = '';
		$info->last_name = '';
		$info->birth_date = '';
		$info->name = '';
		$info->active = '';
		$info->position = '';

		$final = array(
			'0' => array(
				'info' => $info
			)
		);
	}

	$entries = '';
	foreach ($final as $row) {
		$entries .= "<tr><td class='list-entry'>".getListEntryHTML($row)."</td></tr>";
	}	
	
	$html = "
		<table class='liste search_results'>
			$entries
		</table>
	";

	return $html;
}

?>