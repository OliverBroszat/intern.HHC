<?php
/**
*	Template Name: ZeugnisSpeichernMailSenden
*/
	get_currentuserinfo();

	//webserver
	//$userid = $current_user->ID";

	//local
	$userid = 134;

	$query = "SELECT c.id, prefix, first_name, last_name, birth_date, joined, m.left, ressort
				from contact c join member m on c.id = m.contact where c.id = $userid LIMIT 1";

	$result = $wpdb->get_row($query);

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

	$to =
	$subject
	$message
	
?>