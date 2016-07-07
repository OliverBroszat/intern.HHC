<?php
/**
 * Template Name: Zeugnis
 */

get_header();

/**
 * Wird aufgerufen, falls das Zeugnis bereits vom Mitglied ausgefüllt wurde
 *und jetzt durch den Ressortleiter bewertet werden soll
*/
if($_GET){
    $zeungisID = substr($_GET['ID'], 3, strlen($_GET['ID']) - 6);
    $query = "SELECT c.id, prefix, first_name, last_name, birth_date, joined, m.left, r.name as ressort, aufgaben,
                interneProjekte, workshops, externeProjekte, anmerkungen
	        	from contact c
	        	  join member m on c.id = m.contact
	        	  join zeugnisse z on z.contact = c.id
	        	  join ressort r on m.ressort = r.id
	        	where zeugnisID = $zeungisID";

	$result = $wpdb->get_row($query);

	$aufgaben = $result->aufgaben;
	$interneProjekte = $result->interneProjekte;
	$workshops = $result->workshops;
	$externeProjekte = $result->externeProjekte;
	$anmerkungen = $result->anmerkungen;

	//Wird aufgerufen, wenn das Formular zum ersten Mal durch das Mitglied ausgefüllt wird
	} else {
	$usermail = wp_get_current_user() -> user_email;
	 $query = "SELECT c.id, prefix, first_name, last_name, birth_date, joined, m.left, r.name as ressort
	        	from contact c
	        	  join member m on c.id = m.contact
	        	  JOIN mail ma on ma.contact = c.id
	        	  join ressort r on m.ressort = r.id
	        	where ma.address = '$usermail'";
	$result = $wpdb->get_row($query);
	}

	$vorname = $result->first_name;
    $nachname = $result->last_name;
	$geburtsdatum = $result->birth_date;
	$anfangsdatum = $result->joined;
	$enddatum = $result->left;
	$ressort = strtoupper($result->ressort);

	if($result->prefix == "Herr"){
	    $geschlecht = "Männlich";
	} else {
	    $geschlecht = "Weiblich";
	}

    /**
    *  Wird aufgerufen nachdem das Mitgleid sein Zeugnis angefpordert hat
    *  dient der Speicherung der Eingegebenen Informationen in die Datenbank
    *  der Darstellung der Information, ob die Anforderung des Zeugnisses
    *  erfolgreich war und dem Versand der Email an den Ressortleiter
     */

	if($_POST){
         get_currentuserinfo();

        $useraddress = $current_user->user_email;

        $query = "SELECT first_name, last_name
                        from contact c join member m on c.id = m.contact
                        join mail ma on ma.contact = c.id
                        where ma.address = '$useraddress'";

        $result = $wpdb->get_row($query);

        $first_name = $result->first_name;
        $last_name = $result->last_name;

        $wpdb->insert(
                'zeugnisse',
                array(
                        'contact' => $result->id,
                        'aufgaben' => $_POST['aufgaben'],
                        'interneProjekte' => $_POST['interneProjekte'],
                        'workshops' => $_POST['workshops'],
                        'externeProjekte' => $_POST['externeProjekte'],
                        'anmerkungen' => $_POST['anmerkungen']
                )
        );

        $query = "SELECT address
                    from mail m
                      join member mem on mem.contact = m.contact
                      join ressort r on mem.ressort = r.id
                    where m.description = 'HHC'
                    AND position='Ressortleiter'
                    AND r.id = (SELECT ressort from member where contact = '$userid')";

        $result = $wpdb->get_row($query);

        $zeugnis_url = get_template_directory()."/templates/zeugnis?ID=644$wpdb->insert_id". "823";

        $to = $result->address;
        $subject = "Zeugnisanforderung: $first_name $last_name";
        $message = "Hallo ". substr($to, 0, strpos($to, '.')) . ",\n
                          dein Ressormitglied $first_name $last_name hat ein Arbeitszeugnis angefordert.\n
                          Bitte klicke auf den folgenden Link und fülle das Formular bezüglich der Mitarbeit
                          des Mitglieds aus.\n\n
                          $zeugnis_url";

        if(mail($to, $subject, $message)){
            echo "<div class='msg' style='background-color:green; font-size: 1em;'>
                    Das Zeugnis wurde erfolgreich angefordert.
                    </div>";
        } else{
            echo "<div class='msg' style='background-color:red; font-size: 1em;'>
                    Es ist ein Fehler bei der Übertragung der Daten aufgetreten.
                    </div>";
        }
	}
?>

	<h1>Arbeitszeugnis</h1>

	<form action="#" method='POST' enctype='multipart/form-data'>

		<h2>Persönliche Angaben</h2>

		<table class='form'>
		    <tr>
		        <td width='50%'>
                      Vorname:
                      <input type='text' name='vorname' placeholder="Vorname" value="<?php echo $vorname ?>"/>
                    </td>
                    <td colspan='2'>
                      Nachname:
                      <input type='text' name='nachname' placeholder="Nachname" value="<?php echo $nachname; ?>"/>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Geschlecht:
                      <select name='geschlecht'>
                        <option selected disabled hidden> <?php echo $geschlecht ?></option>
                        <option value='Männknlich'>Männlich</option>
                        <option value='Weiblich'>Weiblich</option>
                      </select>
                    </td>
                    <td>
                      Geburtsdatum:
                      <input name="geburtsdatum" type="date" value="<?php echo $geburtsdatum?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Beschäftigungszeit bei HHC:
                    </td>
                  </tr>
                  <tr>
                    <td>
                      Beginn der Mitgliedschaft:
                      <input name="anfangsdatum" type="date" value="<?php echo $anfangsdatum?>">
                    </td>
                    <td>
                      Ende der Migliedschaft:
                      <input name="enddatum" type="date" value="<?php echo $enddatum?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                                Ressort*:
                      <select name='ressort'>
                        <option selected disabled hidden> <?php echo $ressort ?></option>
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

		<?php if($_GET['ID']){
		echo" <h2> Bewertungsbogen </h2>

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