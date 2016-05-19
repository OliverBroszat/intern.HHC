<?php
/**
 * Template Name: Bewerben
 */

get_header();

?>

<!-- Radio Buttons werden für dieses Formular ein bisschen schöner gemacht :) -->
<style>input[type="radio"] { margin-bottom: 10px; }</style>

<style>

.expandablecontent-list {
	list-style:none;
	padding-left:0;
}

.expandablecontent-bar {
	margin: 0 auto;
    max-width: 720px;
    border-top: 2px solid #dddddd;
    text-align: right;
    border-bottom: 2px solid #dddddd;
    padding: 2px;
    background-color: white;
}

.expandablecontent-content {
	margin: 0 auto;
    max-width: 720px;
    text-align: right;
    padding: 5px;
    background-color: white;
}

</style>

	<h1>Bewerbung ROBIN!</h1>

	<form action="<?php echo get_template_directory_uri(); ?>/functions/register/sql_register.php" method='POST' enctype='multipart/form-data'>	
		
		<h2>Persönliche Angaben</h2>

		<table class='form'>
			<tr>
				<td width='20%'>
					Anrede
				</td>
				<td width='40%'>
					<select name='anrede'>
						<option value='Herr'>Herr</option>
						<option value='Frau'>Frau</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Name
				</td>
				<td>	
					<input type='text' name='vorname' placeholder='Vorname'/>
				</td>
				<td width='40%'>
					<input type='text' name='nachname' placeholder='Nachname'/>
				</td>
			</tr>
			<tr>
				<td>
					Mail Adresse
				</td>
				<td>
					<input type='email' name='email' placeholder='E-Mail'/>
				</td>					
				<td>
					<input type='email' name='mail_check' placeholder='E-Mail wiederholen'/>
				</td>
			</tr>
			<tr>
				<td>
					Telefonnummer
				</td>
				<td>
					<input type='text' name='phone1' placeholder='Telefonnummer'/>
				</td>
			</tr>
			<tr>
				<td>
					Geburtstag
				</td>
				<td colspan='3'>
					<select name='birth_day' style='width: 3em; float: left; margin-right:10px'>
						<?php
						for ($i=1; $i<=31; $i++) {
							echo "<option value='$i'>$i</option>";
						}
						?>
					</select>
					<select name='birth_month' style='width: 5.5em; float: left;  margin-right:10px;'>
						<?php
						$months = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
						for ($i=0; $i<12; $i++) {
							echo "<option value='$i'>$months[$i]</option>";
						}
						?>
					</select>
					<select name='birth_year' style='width: 5em; float: left;  margin-right:10px;'>
						<?php
						$min = date('Y')-18;
						$max = $min-30;
						for ($i=$min; $i>=$max; $i--) {
							echo "<option value='$i'>$i</option>";
						}
						?>
					</select>
				</td>
			</tr>
		</table>

		<br>
		<h2>Anschrift</h2>

		<table class='form'>
			<tr>
				<td width='20%'>
					Straße / Nr.
				</td>
				<td width='30%'>
					<input type='text' name='street' placeholder='Straße'/>
				</td>
				<td width='30%'>
					<input type='text' name='number' placeholder='Nr.'/>
				</td>
				<td width='20%'>
					<input type='text' name='addr_extra' placeholder=' (Zusatz)'/>
				</td>
			</tr>
			<tr>
				<td>
					Wohnort
				</td>
				<td>
					<input type='text' name='postal' placeholder='PLZ'/>
				</td>
				<td>
					<input type='text' name='city' placeholder='Stadt' />
				</td>
			</tr>
		</table>
		
		<br>
		<h2>Studium</h2>
		
		<ul class='expandablecontent-list' style=''>
			<li>
				<div class='expandablecontent-bar'><a href='' class='expandablecontent-bar-delete'>Löschen</a></div>
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
			</li>
		</ul>

		<div id='container_id' class='expandablecontent-container'>
		</div>

		
		<button type='submit' class='registrieren'>Bewerbung abschicken!</button>

	</form>

</body>

<script src='js/expandable_list.js'></script>
<script>
	setup_expandablecontent('container_id', 'unique_ID', 'Template Nr %s');
</script>

</html>