<?php

/*
	Für Listen von Formularen (expandablecontent) am Ende Javascript einfügen,
	das automatisch durch add_content vorhandene einträge einfügt

<td colspan='2'>
				<input type='text' name='mail_description' placeholder='Beschreibung' value='".extractData($data, 'email_description')."' style='width: 40%; margin-right: 10px; float: left;'/>
				<input type='email' name='email' placeholder='E-Mail' value='".extractData($data, 'email')."' style='width: 50%;'/>
			</td>	

*/

function extractData($data, $key) {
	// Tries to get $data[$key]
	// If not possible, return '' instead of NULL
	$resl = $data->$key;
	if ($resl == NULL) {
		return '';
	}
	else {
		return $resl;
	}
}

// Wandelt ein PHP Array mit nativen Datentypen in einen Strin um, der eine
// JS Form eines Arrays mit den selben Daten repräsentiert
function toJSArrayString($data) {
	if ($data == null) {
		return '[]';
	}
	return json_encode($data);
}


function getContactEditTemplate($data) {
	/*
	TEST WERTE FÜR DATA
	$data['mail'] = array(
		array(
			'mail_description' => 'Privat',
			'mail_content' => 'test@123.de'
		),
		array(
			'mail_description' => 'Privat',
			'mail_content' => 'test@123456.de'
		)
	);*/

	$resl = "		
	<h2>Persönliche Angaben</h2>
	<input type='hidden' name='Contact-id' value='".extractData($data['info'], 'id')."'></input>
	<div class='ui segment'>
		<table class='form'>
			<tr>
				<td width='20%'>
					Anrede
				</td>
				<td width='40%'>
					<select name='Contact-prefix'>";

			// Anrede
			$resl .= "<option disabled selected value class='placeholder'>Anrede</option>";
			foreach (array ('Herr', 'Frau') as $value) {
				$selected = '';
				if ($value == extractData($data['info'], 'prefix')) { $selected = 'selected'; }
				$resl .= "<option value='$value' $selected>$value</option>";
			}

		$resl .= "</select>
				</td>
			</tr>
			<tr>
				<td>
					Name
				</td>
				<td>	
					<input type='text' name='Contact-first_name' placeholder='Vorname' required minlength='2' value='".extractData($data['info'], 'first_name')."'/>
				</td>
				<td width='40%'>
					<input type='text' name='Contact-last_name' placeholder='Nachname' required minlength='2' value='".extractData($data['info'], 'last_name')."'/>
				</td>
			</tr>
			<tr>
				<td>
					Geburtstag
				</td>
				<td colspan='3'>
					<input type='date' name='Contact-birth_date' placeholder='YYYY-MM-DD' value='".extractData($data['info'], 'birth_date')."'/>
				</td>
			</tr>
			<tr>
				<td>
					Skype Name
				</td>
				<td colspan='3'>
					<input type='text' name='Contact-skype_name' placeholder='Skype Name' value='".extractData($data['info'], 'skype_name')."'/>
			</tr>
			<tr>
				<td>
					Mail Adresse
				</td>
				<td colspan='2'>
					<exl-container id='mail-list' template='mails' source='mail' min-templates='1'></exl-container>
				</td>
			</tr>
			<style>
				@media screen and (max-width: 500px) {
					.mail_content {
						display: block;
						margin-bottom: 5px;
						width: 100% !important;
					}
				}
			</style>
			<tr>
				<td>
					Telefonnummer
				</td>
				<td colspan='2'>
					<exl-container id='phone-list' template='phones' source='phone' min-templates='1'></exl-container>
				</td>
			</tr>
		</table>
	</div>
	<script>
	Exl.setupExlContainerWithID('mail-list', ".json_encode($data['detail']).");
	Exl.setupExlContainerWithID('phone-list', ".json_encode($data['detail']).");
	</script>";
	return $resl;
}


function getAddressEditTemplate($data) {
	$resl = "<h2>Adressen</h2>

	<exl-container id='address-list' template='addresses' source='address' min-templates='1'></exl-container>
	</script>
	<script>
		Exl.setupExlContainerWithID('address-list', ".json_encode($data['detail']).");
	</script>";
	return $resl;
}


function getStudyEditTemplate($data) {

	$resl = "

		<h2>Studienprofile</h2>

		<exl-container id='study-list' template='studies' source='study' min-templates='1'></exl-container>

		<script>
			Exl.setupExlContainerWithID('study-list', ".json_encode($data['detail']).");
			// var data = " . toJSArrayString($data['studies']) . ";
			// select_option('study_status_study--', data, 'status');
			// select_option('degree-study--', data, 'degree');
			// select_option('school-study--', data, 'school');
		</script>";
	return $resl;

}




function getMemberEditTemplate($data) {

	// Load WP-Functions
	$localhost = array( '127.0.0.1', '::1' ); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
	if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
	    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
	} 
	require_once("$root/wp-load.php");
	

	$ressort = extractData($data['info'], 'name');
	$active = extractData($data['info'], 'active');
	$position = extractData($data['info'], 'position');
	$joined = extractData($data['info'], 'joined');
	$left = extractData($data['info'], 'left');


	$resl = "		
		<h2>HHC Mitgliedschaft</h2>
		<input type='hidden' name='Member-contact[]' value='".extractData($data['info'], 'id')."'></input>
		<div class='ui segment'>
			<table class='form'>
				<tbody>
					<tr>
						<td width='50%'>
							Status*:
							<select name='Member-active'>
								<option disabled selected value class='placeholder'>Status</option>
	";
	// Status
	$status = array('0', '1');
	foreach ($status as $value) {
		$selected = '';
		if ($active == $value) { $selected = 'selected'; }
		$resl .= " <option value='".$value."' $selected>".uppercase(bool_to_lbl($value))."</option>";
	}

	$resl .= "
							</select>
						</td>
						<td colspan='2'>
			                Ressort*: 
							<select name='Member-ressort' required>
								<option disabled selected value class='placeholder'>Ressort</option>
	";

	// Ressort
	global $wpdb;
	$ressorts = $wpdb->get_results("SELECT * FROM Ressort");

	foreach ($ressorts as $key => $value) {
		$selected = '';
		if ($ressort == $value->name) { $selected = 'selected'; }
		$resl .= " <option value='".$value->id."' $selected>".uppercase($value->name)."</option>";
	}

	$resl .= "
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Position*:
							<select name='Member-position'>
								<option disabled selected value class='placeholder'>Position</option>
	";

	// Position
	$positions = array('anwärter', 'mitglied', 'ressortleiter', 'alumni');
	foreach ($positions as $value) {
		$selected = '';
		if ($position == $value) { $selected = 'selected'; }
		$resl .= " <option value='".$value."' $selected>".uppercase($value)."</option>";
	}

	$resl .= "
							</select>
						</td>
						<td>
							Beitritt*: <input type='date' name='Member-joined' placeholder='YYYY-MM-DD' value='".$joined."''>
						</td>
						<td>
							(Austritt): <input type='date' name='Member-left' placeholder='YYYY-MM-DD' value='".$left."'>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	";

	return $resl;
}





function getFileEditTemplate($data) {
	$resl = "<h2>Anlagen</h2>
		<table class='form'>
			<tr><td colspan='2'>
			<exl-container id='file-list' template='files' source='files' min-templates='1'></exl-container>
			</td></tr>
		</table>
		<script>
		Exl.setupExlContainerWithID('file-list', ".json_encode($data['file']).");
		</script>";
	return $resl;
}

?>