<?php
/**
*	Template Name: ZeugnisSpeichernMailSenden
*/
	get_currentuserinfo();
	echo "Userid: $current_user->ID";
	
	$wpdb->insert( 
		'Zeugnis', 
		array(
			'vorname' => $current_user->user_firstname,
			'nachname' => $current_user->user_lastname,
			'geschlecht' => $_POST['geschlecht'],
			'geburtsdatum' => $_POST['geburtsdatum'],
			'anfangsdatum' => $_POST['anfangsdatum'],
			'enddatum' => $_POST['enddatum'],
			'$ressort' => $_POST['ressort'],
			'aufgaben' => $_POST['aufgaben'],
			'interneProjekte' => $_POST['interneProjekte'],
			'workshops' => $_POST['workshops'],
			'externeProjekte' => $_POST['externeProjekte'],
			'anmerkungen' => $_POST['anmerkungen']
		)
	);
	
	
?>