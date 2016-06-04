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
				<input type='text' name='first_name' placeholder='Vorname' value='".extractData($data, 'first_name')."'/>
			</td>
			<td width='40%'>
				<input type='text' name='nachname' placeholder='Nachname' value='".extractData($data, 'last_name')."'/>
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
	for ($i=0; $i<12; $i++) {
		$resl .= "<option value='$i'>".$months[$i]."</option>";
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
	</table>
	<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>
	<script>
	var tmp_mail = \"<td><input id='mail_description-%%FULL-ID%%' class='mail_content' type='text' name='mail_description[]' placeholder='Beschreibung' value='%%DATA-mail_description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='mail_content_%%FULL-ID%%' class='mail_content' type='email' name='email[]' placeholder='E-Mail' value='%%DATA-mail_content%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-mail', 'mail', tmp_mail, ".toJSArrayString($data['mail']).", 1);

	var tmp_phone = \"<td><input id='phone_description-%%FULL-ID%%' class='phone_content' type='text' name='phone_description[]' placeholder='Beschreibung' value='%%DATA-phone_description%%' style='width: 45%; margin-right: 10px;'/></td><td><input id='phone_content_%%FULL-ID%%' class='phone_content' type='text' name='phone[]' placeholder='Telefonnummer' value='%%DATA-number%%' style='width: 45%;'/></td>\";
	setup_expandablecontent('expandablecontent-phone', 'phone', tmp_phone, ".toJSArrayString($data['phone']).", 1);
	</script>";
	return $resl;
}


function getAddressEditTemplate($data) {
	/*
	// TEST WERTE FÜR DATA
	$data['address'] = array(
		array(
			'street' => 'Ackerstraße',
			'number' => '109',
			'addr_extra' => '',
			'postal' => '40233',
			'city' => 'Düsseldorf'
		),
		array(
			'street' => 'Gilbachstraße',
			'number' => '9',
			'addr_extra' => '',
			'postal' => '40219',
			'city' => 'Düsseldorf'
		)
	);
	*/
	$resl = "<h2>Anschrift</h2>

		<div id='expandablecontent-address' class='expandablecontent-container'></div><script src='".get_template_directory_uri()."/js/expandable_list.js'></script><script>var tmp_address = \"<table class='form'><tr><td width='20%'>Straße / Nr.</td><td width='30%'><input type='text'name='street[]' placeholder='Straße' value='%%DATA-street%%'/></td><td width='30%'><input type='text' name='number[]'placeholder='Nr.' value='%%DATA-number%%'/></td><td width='20%'><input type='text' name='addr_extra[]' placeholder='(Zusatz)' value='%%DATA-addr_extra%%'/></td></tr><tr><td>Wohnort</td><td><input type='text' name='postal[]'placeholder='PLZ' value='%%DATA-postal%%'/></td><td><input type='text' name='city[]' placeholder='Stadt'value='%%DATA-city%%'/></td></tr></table>\";
		setup_expandablecontent('expandablecontent-address', 'address', tmp_address, ".toJSArrayString($data['address']).", 1);
		</script>";
	return $resl;
}


function getStudyEditTemplate($data) {
	///*
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
			'other_extra' => 'Grundschule'

		),
		array(
			'status_active' => '',
			'status_done' => 'selected',
			'status_cancelled' => ''
		)
	);
	//*/
	$resl = "<h2>Studium</h2>

		<div id='expandablecontent-study' class='expandablecontent-container'>
		</div>
		<script src='".get_template_directory_uri()."/js/expandable_list.js'></script>

		<script>
		var tmp_study = \"<h2>Studium</h2><table class='form'><tr><td style='vertical-align: top;'>Status</td><td style='vertical-align: top;'><select name='study_status[]' id='study_status_%%FULL-ID%%'><option value='active' %%DATA-status_active%%>Aktiv</option><option value='done' %%DATA-status_done%%>Abgeschlossen</option><option value='cancelled' %%DATA-status_cancelled%%>Abgebrochen</option></select></td></tr><tr><td>Abschluss</td><td><select name='abschluss[]' onChange='showDiv(this)'><option value='b sc' %%DATA-b_sc%%>Bachelor of Science</option><option value='m sc' %%DATA-m_sc%%>Master of Science</option><option value='b a' %%DATA-b_a%%>Bachelor of Arts</option><option value='m a' %%DATA-m_a%%>Master of Arts</option><option value='examen' %%DATA-examen%%>Staatsexamen</option><option value='diplom' %%DATA-diplom%%>Diplom</option><option value='other' %%DATA-other%%>anderer Abschluss...</option></select></td><td><input id='hidden_div' type='text' name='anderer_abschluss' placeholder='anderer Abschluss...' value='%%DATA-other_extra%%' style='visibility: hidden;'/></td>\u003cscript\u003efunction showDiv(elem){if(elem.value == 'other') {document.getElementById('hidden_div').style.visibility = 'visible';}else {document.getElementById('hidden_div').style.visibility = 'hidden';}}\u003c/script\u003e</tr><tr><td>Fach</td><td><input type='text' name='course1' placeholder='Fach'/></td><td><input type='text' name='focus1' placeholder='(Schwerpunkt)' /></td></tr><tr><td width ='20%' style='vertical-align: top;'>Universität</td><fieldset id='uni'><td width='40%' style='vertical-align: top;'><input type='radio' name='uni1' value='Heinrich-Heine-Universität'><label for='hhu'> Heinrich-Heine-Universität</label><br><input type='radio' name='uni1' value='FH Düsseldorf'><label for='hhu'> FH Düsseldorf</label><br> <input type='radio' name='uni1'value='Universität Duisburg-Essen'><label for='hhu'> Universität Duisburg-Essen</label><br><input type='radio' name='uni1'value='Universität Köln'><label for='hhu'> Universität Köln</label><br></td><td width='40%' style='vertical-align:top;'><input type='radio' name='uni1' value='FOM'><label for='hhu'> FOM</label><br><input type='radio' name='uni1'value='Bergische Universität Wuppertal'><label for='hhu'> Bergische Universität Wuppertal</label><br><input type='radio'name='uni1' value='andere'><label for='hhu'> andere:</label> <input type='text' name='school1'placeholder='andere Hochschule...'/><br> </td></fieldset></tr><tr><td>Beginn / Ende</td><td style='vertical-align:top;'><input type='date' name='start' placeholder='YYYY-MM'/></td><td><input type='date' name='end' placeholder='YYYY-MM'/></td></tr><tr></tr></table>\";
		setup_expandablecontent('expandablecontent-study', 'study', tmp_study, ".toJSArrayString($data['study']).", 0);</script>";
	return $resl;
}

?>