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

function createBirthdaySelector($withID) {
	$resl = "<select id='day-$withID' name='Contact-birth-day[]' style='width: 3em; float: left; margin-right:10px'>";
	for ($i=1; $i<=31; $i++) {
		$resl .= "<option value='$i'>$i</option>";
	}
	$resl .= "</select>
				<select id='month-$withID' name='Contact-birth_month[]' style='width: 5.5em; float: left;  margin-right:10px;'>";
	$months = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
	for ($i=0; $i<12; $i++) {
		$resl .= "<option value='$i'>".$months[$i]."</option>";
	}
	$resl .= "</select>
				<select id='year-$withID' name='Contact-birth_year[]' style='width: 5em; float: left;  margin-right:10px;'>";
	$min = date('Y')-18;
	$max = $min-30;
	for ($i=$min; $i>=$max; $i--) {
		$resl .= "<option value='$i'>$i</option>";
	}
	$resl .= "</select>";
	return $resl;
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
				Mail Adresse
			</td>
			<td colspan='2'>
				<div id='expandablecontent-mail' class='expandablecontent-container small'></div>
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
				<div id='expandablecontent-phone' class='expandablecontent-container small'></div>
			</td>
		</tr>
	</table>
	<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>
	<script>
	var tmp_mail = \"<td><input id='mail_description-%%FULL-ID%%' class='mail_content' type='text' name='Mail-description[]' placeholder='Beschreibung' value='%%DATA-description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='mail_content_%%FULL-ID%%' class='mail_content' type='email' name='Mail-address[]' placeholder='E-Mail' value='%%DATA-address%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-mail', 'mail', tmp_mail, ".toJSArrayString($data['mails']).", 1);

	var tmp_phone = \"<td><input id='phone_description-%%FULL-ID%%' class='phone_content' type='text' name='Phone-description[]' placeholder='Beschreibung' value='%%DATA-description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='phone_content_%%FULL-ID%%' class='phone_content' type='text' name='Phone-number[]' placeholder='Telefonnummer' value='%%DATA-number%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-phone', 'phone', tmp_phone, ".toJSArrayString($data['phones']).", 1);
	</script>";
	return $resl;
}


function getAddressEditTemplate($data) {
		$resl = "<h2>Adressen</h2>

		<div id='expandablecontent-address' class='expandablecontent-container'></div>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'>
		</script>
		<script>
			var tmp_address = `
				<table class='form'>
					<tr>
						<td colspan='2'>
							<input type='text' name='Address-description[]' placeholder='Beschreibung' value='%%DATA-description%%'>
						</td>
					</tr>
					<tr>
						<td width='20%'>Straße / Nr.</td>
						<td width='30%'>
							<input type='text'name='Address-street[]' placeholder='Straße' value='%%DATA-street%%'/>
						</td>
						<td width='30%'>
							<input type='text' name='Address-number[]'placeholder='Nr.' value='%%DATA-number%%'/>
						</td>
						<td width='20%'>
							<input type='text' name='Address-addr_extra[]' placeholder='(Zusatz)' value='%%DATA-addr_extra%%'/>
						</td>
					</tr>
					<tr>
						<td>Wohnort</td>
						<td>
							<input type='text' name='Address-postal[]'placeholder='PLZ' value='%%DATA-postal%%'/>
						</td>
						<td>
							<input type='text' name='Address-city[]' placeholder='Stadt'value='%%DATA-city%%'/>
						</td>
					</tr>
				</table>
			`;
		setup_expandablecontent('expandablecontent-address', 'address', tmp_address, ".toJSArrayString($data['addresses']).", 1);
		</script>";
	return $resl;
}


function getStudyEditTemplate($data) {
	$root = get_template_directory_uri();
	$study_tmp = file_get_contents("$root/functions/html_templates/study_edit.php");
	$study_tmp = trim(preg_replace('/\s\s+/', ' ', $study_tmp));
	$study_tmp = str_replace(array("\r\n", "\r", "\n"), "", $study_tmp);

	$resl = "

		<h2>Studienprofile</h2>

		<div id='expandablecontent-study' class='expandablecontent-container'></div>

		<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>

		<script>

			var tmp_study = `$study_tmp`;
			setup_expandablecontent('expandablecontent-study', 'study', tmp_study, ".toJSArrayString($data['studies']).", 1);
			


			var data = " . toJSArrayString($data['studies']) . ";

			select_option('study_status_study--', data, 'status');
			select_option('degree-study--', data, 'degree');
			select_option('school-study--', data, 'school');




			// Show/Hide OTHER
			function showDiv(elem) {
				var hiddenDiv = $('#hidden_div-'+elem.id)
				if(elem.value == 'other') {
					hiddenDiv.show();
					hiddenDiv.prop( 'disabled', false );
				} else {
					hiddenDiv.hide();
					hiddenDiv.prop( 'disabled', true );
				}
			}
			

			
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
	";

	return $resl;
}





function getFileEditTemplate($data) {
	$resl = "<h2>Anlagen</h2>
		<table class='form'>
			<tr><td colspan='2'><div id='expandablecontent-files' class='expandablecontent-container small'></div></td></tr>
		</table>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>
		<script>
		var tmp_files = \"<input id='%%desc_FULL-ID%%' type='text'name='File-filedescription[]' placeholder='Bezeichnung' value='%%DATA-filedescription%%' style='width: 45%;'/><input id='file_%%FULL-ID%%' type='file' name='File-apply_file[]' style='width: 45%;'/>\";
		setup_expandablecontent('expandablecontent-files', 'files', tmp_files, ".toJSArrayString($data['files']).", 1);
		</script>";
	return $resl;
}

?>