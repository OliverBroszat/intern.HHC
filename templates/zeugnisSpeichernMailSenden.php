<?php
/**
*	Template Name: ZeugnisSpeichernMailSenden
*/
	get_currentuserinfo();

	//webserver
	//!!!! Funktioniert nur dann so wenn die UserID des Wordpressnutzers = der Nutzerid in der DB ist !!!
	//Alternativ muss die Wordpress id per SQL ermittelt werden
	//$userid = $current_user->ID";

	//local
	$userid = 137;

	$query = "SELECT first_name, last_name
				from contact c join member m on c.id = m.contact where c.id = $userid";

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

echo "Mess: $message";

?>