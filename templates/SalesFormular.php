<?php
/**
 * Template Name: SalesFormular
 */

get_header();

?>
	<h1>Projektdatenbank</h1>
	
		
		<h2>Kundendaten</h2>

		<table class='form'>
			<tr>
				<td>
					<input type='text' name='kunde' placeholder='Kunde/Firmenname'/>
				</td>
				<td width='100%'>
					<select name='verhältnis'>
						<option value="" disabled selected>Verhältnis</option>
						<option value='Projektkunde'>Projektkunde</option>
						<option value='Kurator'>Kurator</option>
						<option value='Kooperation'>Kooperation</option>
						<option value='Mentor'>Mentor</option>
						<option value='Sponsor'>Sponsor</option>
						
					</select>
				</td>
			</tr>
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
					<input type='text' name='vorname' placeholder='Vorname'/>
				</td>
				<td colspan='2'>
					<input type='text' name='nachname' placeholder='Nachname'/>
				</td>
			</tr>
			<tr>
				<td>
					<input type='email' name='mail' placeholder='E-Mail'/>
				</td>					
				<td colspan='2'>
					<input type='text' name='phone' placeholder='Telefonnummer'/>
				</td>
			</tr>
			
			<tr>
				<td>
					<input type='text' name='street' placeholder='Straße'/>
				</td>
				<td>
					<input type='text' name='number' placeholder='Haus-Nr.'/>
				</td>
				
			</tr>
			<tr>
				<td>
					<input type='text' name='city' placeholder='Stadt' />
				</td>
				<td>
					<input type='text' name='postal' placeholder='Postleitzahl'/>
				</td>
				
			</tr>
			<tr>
				<td>
					<input type='text' name='status' placeholder='Status'/>
				</td>
				<td>
					<input type='text' name='projektzeitraum' placeholder='Projektzeitraum'>
				</td>
				
			</tr>
		
			<tr>
				<td>
					 
      				<label for="text">Notizen (ggf. Besonderheiten)</label>
         			<textarea name="Notizen" rows="6" cols="40"></textarea>
  				
				 </td>
  			</tr> 
		</table>

   				

	


		
		<button type='submit' class='registrieren'>Abschicken</button>

	</form>

</body>

</html>