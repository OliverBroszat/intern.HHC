<?php
/**
 * Template Name: Zeugnis
 */

get_header();


if($_GET){
	$vorname = $_GET['vorname'];
	$nachname = $_GET['nachname'];
	$geschlecht = $_GET['geschlecht'];
	$geburtsdatum = $_GET['geburtsdatum'];
	$anfangsdatum = $_GET['anfangsdatum'];
	$enddatum = $_GET['enddatum'];	
	$ressort = $_GET['ressort'];
	$aufgaben = $_GET['aufgaben'];
	$interneProjekte = $_GET['interneProjekte'];
	$workshops = $_GET['workshops'];
	$externeProjekte = $_GET['externeProjekte'];
	$anmerkungen = $_GET['anmerkungen'];
	
	} else {
		$vorname = "Vorname";
		$nachname = "Nachname";
		$geschlecht = "Geschlecht";
		$geburtsdatum = "Geburtsdatum";
		$ressort = "Ressort";
	}

?>

	<h1>Arbeitszeugnis</h1>

	<form action="<?php echo "../zeugnisverarbeiten" ?>"method='POST' enctype='multipart/form-data'>	
		
		<h2>Persönliche Angaben</h2>

		<table class='form'>
			<tr>
				<td width='50%'>
					Vorname:
					<input type='text' name='vorname' placeholder="<?php echo $vorname; ?>"/>
				</td>
				<td colspan='2'>
					Nachname:
					<input type='text' name='nachname' placeholder="<?php echo $vorname; ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					Geschlecht:
					<select name='geschlecht'>
						<option value='Männlich'>Männlich</option>
						<option value='Weiblich'>Weiblich</option>
					</select>
				</td>
				<td>
					Geburtsdatum:
					<input name="geburtsdatum" type="date">
				</td>
			</tr>
			<tr>
				<td>
					Beschäftigungszeit bei HHC:
				</td>
			</tr>
			<tr>
				<td>
					Beginn der Mitgleidschaft:
					<input name="anfangsdatum" type="date" placeholder='YYYY-MM-DD'>
				</td>
				<td>
					Ende der Migliedschaft:
					<input name="enddatum" type="date" placeholder='YYYY-MM-DD'>
				</td>
			</tr>
			<tr>
				<td>
                    Ressort*: 
					<select name='ressort'>
						<option value='vorstand'>Vorstand</option>
						<option value='alumni'>Alumni</option>
						<option value='it'>IT</option>
						<option value='sales'>Sales</option>
						<option value='ope'>OPE</option>
						<option value='finance'>Finanzen und Recht</option>
						<option value='mpr'>MPR</option>
						<option value='qm'>QM</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Aufgaben im Ressort:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="aufgaben" cols="40" rows="5" style="resize: none" placeholder="Beschreibe die Aufgaben, die du in deinem Ressort übernommen hast in Aussagekräftigen Sätzen."></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Interne Projekte:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="interneProjekte" cols="40" rows="5" style="resize: none" placeholder="Beschreibe die Projekte, die du in deinem Ressort übernommen hast in Aussagekräftigen Sätzen."></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Teilnahme an Workshops:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="workshops" cols="40" rows="5" style="resize: none" placeholder="Nenne uns die Workshops an denen du teilgenommen hast, wer den Workshop gehalten hat und welche Themen behandelt wurden."></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Externe Projekte:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="externeProjekte" cols="40" rows="5" style="resize: none" placeholder="Beschreibe die Externen Projekte an denen du teilgenommen hast, und welche Aufgaben du übernommen hast."></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Anmerkungen:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="anmerkungen" cols="40" rows="5" style="resize: none" placeholder="Anmerkungen"></textarea>
				</td>
			</tr>
		</table>
		
		<button type='submit' name='submit' class='registrieren'> Submit </button>
				
				<h2> Bewertungsbogen </h2>
				
		<table class='form'>
			<tr>
				<td>
				</td>
				
				<td>
					Sehr gut
				</td>
				<td>
					Gut
				</td>
				<td>
					Befriedigend
				</td>
				<td>
					Ausreichend
				</td>
				<td>
					Mangelhaft
				</td>
				<td>
					Ungenügend
				</td>
			</tr>
			
			<?php printBewertungsReihe("Leistungsbereitschaft") ?>
			<?php printBewertungsReihe("Weiterbildung") ?>
			<?php printBewertungsReihe("Arbeitsweise") ?>
			<?php printBewertungsReihe("Arbeitsergebnis") ?>
			<?php printBewertungsReihe("Sozialverhalten") ?>
			<?php printBewertungsReihe("Zuverlässigkeit") ?>
		</table>
		
		<button type='submit' class='registrieren'>Registrieren!</button>

	</form>

</body>

</html>

<?php
	function printBewertungsReihe($value){
		$table = "<tr>
				<td>
					$value
				</td>
				<td>
					<input type='checkbox' name='$value.1'>
				</td>
				<td>
					<input type='checkbox' name='$value.2'>
				</td>
				<td>
					<input type='checkbox' name='$value.3'>
				</td>
				<td>
					<input type='checkbox' name='$value.4'>
				</td>
				<td>
					<input type='checkbox' name='$value.5'>
				</td>
				<td>
					<input type='checkbox' name='$value.6'>
				</td>
			</tr>";
			
			echo $table;
	};

?>