<?php

function getContactEdit() {
	return "		
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
				<div id='expandablecontent-phone' class='expandablecontent-container'>
				</div>
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
	</table>";
}

?>