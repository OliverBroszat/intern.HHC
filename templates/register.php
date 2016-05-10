<?php
/**
 * Template Name: Registrieren
 * Author: Daniel
 * Status: 05.04.2016, 19:00 Uhr
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

// Server:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/twentyfourteen-child/functions/main_functions.php");

echo html_header('Registrieren');

?>

<html>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
	<title>Neu registrieren!</title>
</head>
<body>

	<h1>Mitgliederdatenbank</h1>
	
	<div class="msg">
		Hallo werter HHCler!<br>
		Bitte trage hier deine Daten ein<br><br>
		
		Alle mit einem <b>*</b> markierten Felder sind <b>Pflichtfelder</b>.<br>
		
		Ein Profilbild ist keine Pflicht. Es wäre aber für alle sehr wünschenswert :)<br>
		Am besten wäre ein Foto, das du auch als <b>Bewerbungsfoto</b> nehmen würdest.<br>
		Das Profilbild sollte <b>quadratisch</b> und <b>kleiner als 10 MB</b> sein.<br>
	</div>

	<form action='../sql-register' method='POST' enctype='multipart/form-data'>	
		
		<h2>Persönliche Angaben</h2>

		<table class='form'>
			<tr>
				<td>
					<select name='anrede'>
						<option value='Herr'>Herr</option>
						<option value='Frau'>Frau</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width='50%'>	
					<input type='text' name='vorname' placeholder='Vorname*'/>
				</td>
				<td colspan='2'>
					<input type='text' name='nachname' placeholder='Nachname*'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='email' name='mail_privat' placeholder='E-Mail (privat)*'/>
				</td>					
				<td colspan='2'>
					<input type='email' name='mail_hhc' placeholder='E-Mail (HHC)*'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='text' name='phone1' placeholder='Tel.-Nr, 1 (Mobil)*'/>
				</td>
				<td colspan='2'>
					<input type='text' name='phone2' placeholder='(Tel.-Nr. 2 (Privat))'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='text' name='street' placeholder='Straße*'/>
				</td>
				<td>
					<input type='text' name='number' placeholder='Haus-Nr.*'/>
				</td>
				<td>
					<input type='text' name='addr_extra' placeholder='(Zusatz)'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='text' name='city' placeholder='Stadt*' />
				</td>
				<td>
					<input type='text' name='postal' placeholder='PLZ*'/>
				</td>
				<td>
					Geburtsdatum*: <input type='date' name='birth_date' placeholder='YYYY-MM-DD'/>
				</td>
			</tr>
			<tr>
				<td colspan='3'>
					Profilbild hochladen:
					<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
	<input type="hidden" name="post_id" id="post_id" value="55" />
	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
				</td>
			</tr>
		</table>
		
		<h2>HHC</h2>
		
		<table class='form'>
			<tr>
				<td width='50%'>
					Status*:
					<select name='active'>
						<option value='0'>Aktiv</option>
						<option value='1'>Inaktiv</option>
					</select>
				</td>
				<td colspan='2'>
                    Ressort*: 
					<select name='ressort'>
						<option value='unbekannt'>Kein Ressort</option>
						<option value='vorstand'>Vorstand</option>
						<option value='it'>IT</option>
						<option value='mpr'>Marketing und PR</option>
						<option value='ope'>Organisation und Personalentwicklung</option>
						<option value='sales'>Sales</option>
						<option value='finance'>Finanzen und Recht</option>
						<option value='qm'>Qualitäts- und Alumnimanagement</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Position*:
					<select name='position'>
						<option value='anwärter'>Anwärter</option>
						<option value='mitglied'>Mitglied</option>
						<option value='ressortleiter'>Ressortleiter</option>
						<option value='alumni'>Alumni</option>
					</select>
				</td>
				<td>
					Beitritt*: <input type='date' name='start' placeholder='YYYY-MM-DD'/>
				</td>
				<td>
					(Austritt): <input type='date' name='end' placeholder='YYYY-MM-DD'/>
				</td>
			</tr>
		</table>
		
		<h2>Studium</h2>
		
		<table class='form'>
			<tr>
				<td width='50%'>
					<fieldset id="uni">
    <input type="radio" name="uni1" value="Heinrich-Heine-Universität"><label for="hhu"> Heinrich-Heine-Universität</label><br> 
	<input type="radio" name="uni1" value="FH Düsseldorf"><label for="hhu"> FH Düsseldorf</label><br> 
	<input type="radio" name="uni1" value="Universität Duisburg-Essen"><label for="hhu"> Universität Duisburg-Essen</label><br>
	<input type="radio" name="uni1" value="Universität Köln"><label for="hhu"> Universität Köln</label><br>
	<input type="radio" name="uni1" value="FOM"><label for="hhu"> FOM</label><br>
	<input type="radio" name="uni1" value="andere"><label for="hhu"> andere:</label> <input type='text' name='school1' placeholder='andere Hochschule...'/><br> 
  </fieldset>
				</td>
				<td colspan='2'>
					<input type='text' name='course1' placeholder='Fach*'/>
				</td>
			</tr>
			<tr>
				<td>
					Status*:
					<select name='status1'>
						<option value='active'>Aktiv</option>
						<option value='done'>Beendet</option>
						<option value='cancelled'>Abgebrochen</option>
					</select>
				</td>
				<td>
					Beginn*: <input type='date' name='start1' placeholder='YYYY-MM-DD'/>
				</td>
				<td>
					(Ende): <input type='date' name='end1' placeholder='YYYY-MM-DD'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='text' name='focus1' placeholder='(Fokus/Schwerpunkt)' />
				</td>
				<td colspan='2'>
					<input type='text' name='extra1' placeholder='Abschluss' />
				</td>
			</tr>
		</table>

		<h2>(Studium 2)</h2>
		<fieldset style="text-align: center; margin-bottom: 20px;">
			<label>
				<input type="checkbox" name="add_studium2" value="study2">
				Zweites Studienprofil hinzufügen
			</label>
		</fieldset>
		
		<table class='form'>
			<tr>
				<td width='50%'>
					<fieldset id="uni">
    <input type="radio" name="uni2" value="Heinrich-Heine-Universität"><label for="hhu"> Heinrich-Heine-Universität</label><br> 
	<input type="radio" name="uni2" value="FH Düsseldorf"><label for="hhu"> FH Düsseldorf</label><br> 
	<input type="radio" name="uni2" value="Universität Duisburg-Essen"><label for="hhu"> Universität Duisburg-Essen</label><br>
	<input type="radio" name="uni2" value="Universität Köln"><label for="hhu"> Universität Köln</label><br>
	<input type="radio" name="uni2" value="FOM"><label for="hhu"> FOM</label><br>
	<input type="radio" name="uni2" value="andere"><label for="hhu"> andere:</label> <input type='text' name='school2' placeholder='andere Hochschule...'/><br> 
  </fieldset>
				</td>
				<td colspan='2'>
					<input type='text' name='course2' placeholder='Fach'/>
				</td>
			</tr>
			<tr>
				<td>
					Status:
					<select name='status2'>
						<option value='active'>Aktiv</option>
						<option value='done'>Beendet</option>
						<option value='cancelled'>Abgebrochen</option>
					</select>
				</td>
				<td>
					Beginn*: <input type='date' name='start2' placeholder='YYYY-MM-DD'/>
				</td>
				<td>
					(Ende): <input type='date' name='end2' placeholder='YYYY-MM-DD'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='text' name='focus2' placeholder='Fokus/Schwerpunkt' />
				</td>
				<td colspan='2'>
					<input type='text' name='extra2' placeholder='Abschluss' />
				</td>
			</tr>
		</table>
		
		<button type='submit' class='registrieren'>Registrieren!</button>

	</form>

<?php
echo html_footer();
?>