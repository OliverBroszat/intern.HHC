<?php 
/* 
	AcceptPost.php

	Diese PHP Datei enthält die Funktion acceptPost(), welche die automatisch übergebene
	POST Variable nimmt, und die darin enthaltenen Daten in eine neue Datenstruktur folgender Form bringt:

	$input = array(
		"search" => array(suchwort1, suchwort2, suchwort3, ...),
		"filter" => array(
						"Member.position" => array(position1, position2, ...),
						"Member.active" => array(...),
						"Ressort.name" => array("IT", "Vorstand", ...)
					)
		"ajax_call" => true/false (erstmal immer False! Müssen wir noch mal drüber sprechen)
	)

	Die Variable $input ist die Rückgabe der Funktion acceptPost()
*/


function AcceptPost($post, $get){

	// Filter
	$filter = array(
		"Ressort.name" => $post['f_ressort_list'],
		"Member.position" => $post['f_position_list'],
		"Member.active" => $post['f_status_list']
	);

	// Suchworte
	$search_text = $get['search_text'];

	// Sortierkriterium
	$sort = $post['sort'];

	// ASC/DESC
	$order = 'ASC';


	$input = array(
		'search' => $search_text,
		'filter' => $filter,
		'sort' => $sort,
		'order' => $order,
		'ajax_call' => false
	);


	return $input;
}

?>
