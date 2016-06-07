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
	$resl = $data[$key];
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
			'mail_content' => 'test@123.de',
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
				<select name='prefix'>";
	if (extractData($data, 'prefix') != 'Frau') {
		$resl .= "<option value='Herr' selected='selected'>Herr</option><option value='Frau'>Frau</option>";
	}
	else {
		$resl .= "<option value='Herr'>Herr</option><option value='Frau' selected='selected'>Frau</option>";
	}
	$resl .= "</select>
			</td>
		</tr>
		<tr>
			<td>
				Name
			</td>
			<td>	
				<input type='text' name='first_name' placeholder='Vorname' value='".extractData($data['info'], 'first_name')."'/>
			</td>
			<td width='40%'>
				<input type='text' name='nachname' placeholder='Nachname' value='".extractData($data['info'], 'last_name')."'/>
			</td>
		</tr>
		<tr>
			<td>
				Geburtstag
			</td>
			<td colspan='3'>
				<select name='birth_day' style='width: 3em; float: left; margin-right:10px'>";
	for ($i=1; $i<=31; $i++) {
		$resl .= "<option value='$i'>$i</option>";
	}
	$resl .= "</select>
				<select name='birth_month' style='width: 5.5em; float: left;  margin-right:10px;'>";
	$months = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
	for ($i=1; $i<13; $i++) {
		$resl .= "<option value='$i'>".$months[$i-1]."</option>";
	}
	$resl .= "</select>
				<select name='birth_year' style='width: 5em; float: left;  margin-right:10px;'>";
	$min = date('Y')-18;
	$max = $min-30;
	for ($i=$min; $i>=$max; $i--) {
		$resl .= "<option value='$i'>$i</option>";
	}
	$resl .= "</select>
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
	var tmp_mail = \"<td><input id='mail_description-%%FULL-ID%%' class='mail_content' type='text' name='mail_description[]' placeholder='Beschreibung' value='%%DATA-mail_description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='mail_content_%%FULL-ID%%' class='mail_content' type='email' name='email[]' placeholder='E-Mail' value='%%DATA-mail_content%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-mail', 'mail', tmp_mail, ".toJSArrayString($data['mails']).", 1);

	var tmp_phone = \"<td><input id='phone_description-%%FULL-ID%%' class='phone_content' type='text' name='phone_description[]' placeholder='Beschreibung' value='%%DATA-phone_description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='phone_content_%%FULL-ID%%' class='phone_content' type='text' name='phone[]' placeholder='Telefonnummer' value='%%DATA-number%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-phone', 'phone', tmp_phone, ".toJSArrayString($data['phones']).", 1);
	</script>";
	return $resl;
}


function getAddressEditTemplate($data) {
	/*
	// TEST WERTE FÜR DATA
	$data['address'] = array(
		array(
			'addr_description' => 'Alte Adresse',
			'street' => 'Ackerstraße',
			'number' => '109',
			'addr_extra' => '',
			'postal' => '40233',
			'city' => 'Düsseldorf'
		),
		array(
			'addr_description' => 'Neue Adresse',
			'street' => 'Gilbachstraße',
			'number' => '9',
			'addr_extra' => '',
			'postal' => '40219',
			'city' => 'Düsseldorf'
		)
	);
	*/
	$resl = "<h2>Anschrift</h2>

		<div id='expandablecontent-address' class='expandablecontent-container'></div>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'>
		</script>
		<script>
		var tmp_address = \"<table class='form'><tr><td>Beschreibung</td><td colspan='2'><input type='text' name='addr_description[]' placeholder='Privat/Geschäftlich/...' value='%%DATA-addr_description%%' /></td></tr><tr><td width='20%'>Straße / Nr.</td><td width='30%'><input type='text'name='street[]' placeholder='Straße' value='%%DATA-street%%'/></td><td width='30%'><input type='text' name='number[]'placeholder='Nr.' value='%%DATA-number%%'/></td><td width='20%'><input type='text' name='addr_extra[]' placeholder='(Zusatz)' value='%%DATA-addr_extra%%'/></td></tr><tr><td>Wohnort</td><td><input type='text' name='postal[]'placeholder='PLZ' value='%%DATA-postal%%'/></td><td><input type='text' name='city[]' placeholder='Stadt'value='%%DATA-city%%'/></td></tr></table>\";
		setup_expandablecontent('expandablecontent-address', 'address', tmp_address, ".toJSArrayString($data['address']).", 1);
		</script>";
	return $resl;
}


function getStudyEditTemplate($data) {
	/*
	// TEST WERTE FÜR DATA
	$data['study'] = array(
		array(
			'status_active' => '',
			'status_done' => '',
			'status_cancelled' => 'selected',
			'b_sc' => '',
			'm_sc' => '',
			'b_a' => '',
			'm_a' => '',
			'examen' => '',
			'diplom' => '',
			'other' => 'selected',
			'other_extra' => 'Grundschule',
			'other_visibility' => 'visible',
			'course' => 'Informatik',
			'focus' => 'Deine Mutter',
			'checked-HHU' => '',
			'checked-FH' => '',
			'checked-DUE' => 'checked=\"checked\"',
			'checked-KOELN' => '',
			'checked-FOM' => '',
			'checked-WUPPERTAL' => '',
			'checked-OTHER' => '',
			'other-text' => '',
			'start-date' => '2007-06-01',
			'end-date' => ''
		)
	);
	*/
	$root = get_template_directory_uri();
	$study_tmp = file_get_contents("$root/functions/html_templates/study_edit.html");
	$study_tmp = trim(preg_replace('/\s\s+/', ' ', $study_tmp));
	$study_tmp = str_replace(array("\r\n", "\r", "\n"), "", $study_tmp);

	$resl = "<h2>Studium</h2>

		<div id='expandablecontent-study' class='expandablecontent-container'>
		</div>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>

		<script>
		var tmp_study = \"$study_tmp\";
		setup_expandablecontent('expandablecontent-study', 'study', tmp_study, ".toJSArrayString($data['study']).", 1);

		function showDiv(elem) {
			if(elem.value == 'other') {
				document.getElementById('hidden_div'+elem.id).style.visibility = 'visible';
			} else {
				document.getElementById('hidden_div'+elem.id).style.visibility = 'hidden';
			}
		}
		</script>";
	return $resl;
}


function getFileEditTemplate($data) {
	$resl = "<h2>Anlagen</h2>
		<table class='form'>
			<tr><td colspan='2'><div id='expandablecontent-files' class='expandablecontent-container small'></div></td></tr>
		</table>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>
		<script>
		var tmp_files = \"<input id='%%desc_FULL-ID%%' type='text'name='filedescription[]' placeholder='Bezeichnung' value='%%DATA-filedescription%%' style='width: 45%;'/><input id='file_%%FULL-ID%%' name='applicationfile[]' type='file' accept='*' style='width: 45%;'/>\";
		setup_expandablecontent('expandablecontent-files', 'files', tmp_files, ".toJSArrayString($data['files']).", 1);
		</script>";
	return $resl;
}

?>