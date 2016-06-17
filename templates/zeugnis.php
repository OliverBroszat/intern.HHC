<?php
/**
 * Template Name: Zeugnis
 */

get_header();


if($_GET){
    $query = "SELECT c.id, prefix, first_name, last_name, birth_date, joined, m.left, ressort, aufgaben,
                interneProjekte, workshops, externeProjekte, anmerkungen
	        	from contact c
	        	  join member m on c.id = m.contact
	        	  join zeugnisse z on z.contact = c.id
	        	where c.id = $userid LIMIT 1";

	$result = $wpdb->get_row($query);

	if($result->prefix == "Herr"){
	    $geschlecht = "Männlich";
	} else {
	    $geschlecht = "Weiblich";
	}

	$geburtsdatum = $result->birth_date;
	$anfangsdatum = $result->joined;
	$enddatum = $result->left;
	$ressort = $result->ressort;
	$aufgaben = $result->aufgaben;
	$interneProjekte = $result->interneProjekte;
	$workshops = $result->workshops;
	$externeProjekte = $result->externeProjekte;
	$anmerkungen = $result->anmerkungen;
	
	}
?>

	<h1>Arbeitszeugnis</h1>

	<form action="<?php echo "../zeugnisverarbeiten" ?>"method='POST' enctype='multipart/form-data'>	
		
		<h2>Persönliche Angaben</h2>

		<table class='form'>
			<tr>
				<td>
					Aufgaben im Ressort:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="aufgaben" cols="40" rows="5" style="width: 100%; resize: none" placeholder="Beschreibe die Aufgaben, die du in deinem Ressort übernommen hast in Aussagekräftigen Sätzen."><?php echo $aufgaben ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Interne Projekte:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="interneProjekte" cols="40" rows="5" style="width: 100%; resize: none" placeholder="Beschreibe die Projekte, die du in deinem Ressort übernommen hast in Aussagekräftigen Sätzen."><?php echo $interneProjekte ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Teilnahme an Workshops:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="workshops" cols="40" rows="5" style="width: 100%; resize: none" placeholder="Nenne uns die Workshops an denen du teilgenommen hast, wer den Workshop gehalten hat und welche Themen behandelt wurden."><?php echo $workshops ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Externe Projekte:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="externeProjekte" cols="40" rows="5" style="width: 100%; resize: none" placeholder="Beschreibe die Externen Projekte an denen du teilgenommen hast, und welche Aufgaben du übernommen hast."><?php echo $externeProjekte ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Anmerkungen:
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="anmerkungen" cols="40" rows="5" style="width: 100%; resize: none" placeholder="Anmerkungen"><?php echo $anmerkungen ?></textarea>
				</td>
			</tr>
		</table>


		<?php if(!$_GET){
		    echo "<button type='submit' name='submit' class='registrieren'> Submit </button>";
		}
		?>

				
				<h2> Bewertungsbogen </h2>

		<?php if($_GET){
		echo "<table class='form'>
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
			</tr>";
			
			printBewertungsReihe("Leistungsbereitschaft");
			printBewertungsReihe("Weiterbildung");
			printBewertungsReihe("Arbeitsweise");
			printBewertungsReihe("Arbeitsergebnis");
			printBewertungsReihe("Sozialverhalten");
			printBewertungsReihe("Zuverlässigkeit");
		echo"</table>
		
		<button type='submit' class='registrieren'>Absenden</button>";
        } ?>
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