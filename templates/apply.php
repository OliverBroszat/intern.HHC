<?php
/**
 * Template Name: Bewerben
 */

get_header();

?>

<!-- Radio Buttons werden für dieses Formular ein bisschen schöner gemacht :) -->
<style>
input[type="radio"] { margin-bottom: 10px; }
td {
	vertical-align: top;
}
</style>

<style type='text/css'></style>

	<h1>Bewerbung</h1>

	<form action="<?php echo get_template_directory_uri(); ?>/functions/register/sql_register.php" method='POST' enctype='multipart/form-data'>	
		
		<?php getContactEdit(); ?>

		<br>
		<h2>Anschrift</h2>

		<div id='expandablecontent-address' class='expandablecontent-container'>
		</div>

		
		<br>
		<h2>Studium</h2>
		
		<table class='form'>
			<tr>
				<td style='vertical-align: top;'>
					Status
				</td>
				<td style='vertical-align: top;'>
					<select name='status1'>
						<option value='active'>Aktiv</option>
						<option value='done'>Abgeschlossen</option>
						<option value='cancelled'>Abgebrochen</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Abschluss
				</td>
				<td>
					<select name='abschluss' onChange='showDiv(this)'>
						<option value='b sc'>Bachelor of Science</option>
						<option value='m sc'>Master of Science</option>
						<option value='b a'>Bachelor of Arts</option>
						<option value='m a'>Master of Arts</option>
						<option value='examen'>Staatsexamen</option>
						<option value='diplom'>Diplom</option>
						<option value='other'>anderer Abschluss...</option>
					</select>
				</td>
				<td>
					<input id='hidden_div' type='text' name='anderer_abschluss' placeholder='anderer Abschluss...' style='visibility: hidden;'/>
				</td>
				<script>
					function showDiv(elem){
						if(elem.value == 'other') {
							document.getElementById('hidden_div').style.visibility = "visible";
						}
						else {
							document.getElementById('hidden_div').style.visibility = "hidden";
						}
					}
				</script>
			</tr>
			<tr>
				<td>
					Fach
				</td>
				<td>
					<input type='text' name='course1' placeholder='Fach'/>
				</td>
				<td>
					<input type='text' name='focus1' placeholder='(Schwerpunkt)' />
				</td>
			</tr>
			<tr>
				<td width ='20%' style='vertical-align: top;'>
					Universität
				</td>
				<fieldset id="uni">
					<td width='40%' style='vertical-align: top;'>
    <input type="radio" name="uni1" value="Heinrich-Heine-Universität"><label for="hhu"> Heinrich-Heine-Universität</label><br> 
	<input type="radio" name="uni1" value="FH Düsseldorf"><label for="hhu"> FH Düsseldorf</label><br> 
	<input type="radio" name="uni1" value="Universität Duisburg-Essen"><label for="hhu"> Universität Duisburg-Essen</label><br>
	<input type="radio" name="uni1" value="Universität Köln"><label for="hhu"> Universität Köln</label><br>
					</td>
					<td width='40%' style='vertical-align: top;'>
	<input type="radio" name="uni1" value="FOM"><label for="hhu"> FOM</label><br>
	<input type="radio" name="uni1" value="Bergische Universität Wuppertal"><label for="hhu"> Bergische Universität Wuppertal</label><br>
	<input type="radio" name="uni1" value="andere"><label for="hhu"> andere:</label> <input type='text' name='school1' placeholder='andere Hochschule...'/><br> 
					</td>
				</fieldset>
			</tr>
			<tr>
				<td>
					Beginn / Ende
				</td>

				<td style='vertical-align: top;'>
					<input type='date' name='start' placeholder='YYYY-MM'/>
				</td>
				<td>
					<input type='date' name='end' placeholder='YYYY-MM'/>
				</td>
			</tr>
			<tr>
				
				
			</tr>
		</table>
		
		<button type='submit' class='registrieren'>Bewerbung abschicken!</button>

	</form>

</body>

<!-- JavaScript einbinden -->
<script src="<?php echo get_template_directory_uri(); ?>/js/expandable_list.js"></script>
<script>
var template = "<h3 style='text-align: center;'>Tabelle %ELEMENT-ID%</h3><table><tr><td>1</td><td>2</td><td>3</td></tr><tr><td>4</td><td colspan='2'>%FULL-ID%</td></tr></table>";

var tmp_phone = "<input id='phone-%ELEMENT-ID%' type='text' name='phone[]' placeholder='Telefonnummer'/>";
setup_expandablecontent('expandablecontent-phone', 'phone', tmp_phone);

var tmp_address = "<table class='form'><tr><td width='20%'>Straße / Nr.</td><td width='30%'><input type='text' name='street[]' placeholder='Straße'/></td><td width='30%'><input type='text' name='number[]' placeholder='Nr.'/></td><td width='20%'><input type='text' name='addr_extra[]' placeholder=' (Zusatz)'/></td></tr><tr><td>Wohnort</td><td><input type='text' name='postal[]' placeholder='PLZ'/></td><td><input type='text' name='city[]' placeholder='Stadt' /></td></tr></table>";
setup_expandablecontent('expandablecontent-address', 'address', tmp_address);
</script>

</html>