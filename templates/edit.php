<?php
/*
 * Template Name: Edit
 */


$root = get_template_directory();

require_once("$root/functions/main_functions.php");
require_once("$root/functions/suchfunktion/AcceptPost.php");
require_once("$root/functions/suchfunktion/prepareSQL.php");
require_once("$root/functions/suchfunktion/getData.php");
require_once("$root/functions/suchfunktion/postProcess.php");
require_once("$root/functions/edit/createHTML.php");


/*	Fenster zum Bearbeiten von Mitgliederinformationen.
	Enthält quasi die gleichen Informationen wie ein Eintrag aus der Mitgliederliste.
	Kann jedoch bearbeitet werden.
*/

$get = array('search_text' => $_POST['id']);
$post = array(
	'f_ressort_list' => array(),
	'f_position_list' => array(),
	'f_status_list' => array(),
	'sort' => ''
);

$search_select = array(
	'Contact' => array(
		'id',
		'prefix',
		'first_name',
		'last_name',
		'birth_date',
		'comment'
	),
	'Ressort' => array(
		'name'
	),
	'Member' => array(
		'active',
		'position',
		'joined',
		'left'
	)
);

$search_range = array(
	'Contact' => array(
		'id'
	)
);



// POST übertragen
$input = AcceptPost($post, $get);
// SQL-Abfrage vorbereiten
$queries = prepareSQL($input, $search_select, $search_range);
// Datenbankabfrage
$data = getData($queries);
// Post-Processing
$final = postProcess($data);
// HTML-Tabelle
$html = createHTML($final);


/* 
----------------------------------------
---------- HTML-Seite ---------- 
----------------------------------------
*/

?>
<form method='POST'>
	<div id='list-container'>
		<?php echo $html ?>
	</div>
</form>

