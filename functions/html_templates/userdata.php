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

function createBirthdaySelector($withID) {
	$resl = "<select id='day-$withID' name='birth_day[]' style='width: 3em; float: left; margin-right:10px'>";
	for ($i=1; $i<=31; $i++) {
		$resl .= "<option value='$i'>$i</option>";
	}
	$resl .= "</select>
				<select id='month-$withID' name='birth_month[]' style='width: 5.5em; float: left;  margin-right:10px;'>";
	$months = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
	for ($i=0; $i<12; $i++) {
		$resl .= "<option value='$i'>".$months[$i]."</option>";
	}
	$resl .= "</select>
				<select id='year-$withID' name='birth_year[]' style='width: 5em; float: left;  margin-right:10px;'>";
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
				<select name='birth_day' style='width: 3em; float: left; margin-right:10px'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option></select>
				<select name='birth_month' style='width: 5.5em; float: left;  margin-right:10px;'><option value='1'>Januar</option><option value='2'>Februar</option><option value='3'>März</option><option value='4'>April</option><option value='5'>Mai</option><option value='6'>Juni</option><option value='7'>Juli</option><option value='8'>August</option><option value='9'>September</option><option value='10'>Oktober</option><option value='11'>November</option><option value='12'>Dezember</option></select>
				<select name='birth_year' style='width: 5em; float: left;  margin-right:10px;'><option value='1998'>1998</option><option value='1997'>1997</option><option value='1996'>1996</option><option value='1995'>1995</option><option value='1994'>1994</option><option value='1993'>1993</option><option value='1992'>1992</option><option value='1991'>1991</option><option value='1990'>1990</option><option value='1989'>1989</option><option value='1988'>1988</option><option value='1987'>1987</option><option value='1986'>1986</option><option value='1985'>1985</option><option value='1984'>1984</option><option value='1983'>1983</option><option value='1982'>1982</option><option value='1981'>1981</option><option value='1980'>1980</option><option value='1979'>1979</option><option value='1978'>1978</option><option value='1977'>1977</option><option value='1976'>1976</option><option value='1975'>1975</option><option value='1974'>1974</option><option value='1973'>1973</option><option value='1972'>1972</option><option value='1971'>1971</option><option value='1970'>1970</option><option value='1969'>1969</option><option value='1968'>1968</option></select>
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

?>