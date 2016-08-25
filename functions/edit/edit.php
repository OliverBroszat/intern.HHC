<?php

/*
	Diese Datei wird über AJAX (/js/ajax_edit.js) aufgerufen, fragt zu einer ID die Daten ab und gibt das Ergebnis in der gewünschten Form zurück.
*/

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

$root = get_template_directory();
require_once("$root/functions/suchfunktion/prepareSQL.php");
require_once("$root/functions/suchfunktion/getData.php");
require_once("$root/functions/html_templates/userdata.php");


$id = $_POST['id'];

// SQL-Abfrage vorbereiten
$queries = prepareSQL($id);
// Datenbankabfrage
$data = getData($queries)[$id];
// echo '***********IN EDIT.PHP************<br><br>';
// var_dump(json_encode($data));
// echo '<br><br>*********************************';


// Study-Daten müssen überarbeitet werden, damit Sie Mustache-Konform sind

$studyDataFormatted = array(
	'study' => array(
		array(
			'id' => 299,
			'contact' => 199,
			'course' => 'Informatik',
			'focus' => 'Algorithmen und Datenstrukturen',
			'start' => '2012-10-01',
			'end' => '0000-00-00',
			'no-status-selected' => '',
			'status' => array(
				array('name' => 'active', 'selected' => ''),
				array('name' => 'cancelled', 'selected' => ''),
				array('name' => 'done', 'selected' => 'selected')
			),
			'no-degree-selected' => '',
			'other-degree-selected' => '',
			'other-degree-text' => '',
			'degrees' => array(
				array('name' => 'Bachelor of Science', 'selected' => ''),
				array('name' => 'Master of Science', 'selected' => ''),
				array('name' => 'Bachelor of Arts', 'selected' => 'selected'),
				array('name' => 'Master of Arts', 'selected' => '')
			),
			'no-school-selected' => '',
			'other-school-selected' => false,
			'other-school-text' => '',
			'schools' => array(
				array('name' => 'Heinrich Heine Universität', 'selected' => 'selected'),
				array('name' => 'FH Düsseldorf', 'selected' => ''),
				array('name' => 'Universität Duisburg Essen', 'selected' => ''),
				array('name' => 'Universität Köln', 'selected' => 'selected'),
				array('name' => 'FOM', 'selected' => ''),
				array('name' => 'Bergische Universität Wuppertal', 'selected' => '')
			)
		)
	)
);
$testData2 = array('detail' => $studyDataFormatted);

// echo "<br><br>*************************<br>";
// arr_to_list($data);
// echo "<br>*************************<br><br>";

// Text für den Schließen-Dialog
$dialog = "Wollen Sie das Fenster wirklich ohne zu speichern schließen? Ungespeicherte Änderungen gehen verloren.";

// Finale HTML-Ausgabe
$html = "
	<div id='edit'>
		<form id='edit-form' method='POST' action='".get_template_directory_uri()."/functions/edit/sql_edit.php' enctype='multipart/form-data'>
			<div id='popup-content'>

				<h2>Profilbild</h2>
				<div class='ui segment edit-image clearfix'>
					<div class='edit-image-image'>
						".$data['image']."
					</div>
					<div class='edit-image-buttons'>
						<input type='file' accept='image/*' onchange='$(\"#delete_image\").val(\"false\"); loadFile(event);' class='full-width' id='edit-upload-image' placeholder='Upload' name='upload_image'>
						
						<button type='button' class='ui icon button fluid' id='edit-delete-image' style='display:none;' 
							onclick=\"$('#delete_image').val('true')\">
							<i class='remove icon'></i>							
							Löschen &nbsp; / 
							<i class='upload icon'></i>
							Hochladen
						</button>
						<input type='hidden' id='delete_image' name='crud-delete_image' value='false'>
					</div>						
				</div><br>

				".getContactEditTemplate($data)."<br>
				".getAddressEditTemplate($data)."<br>
				".getStudyEditTemplate($testData2)."<br>
				".getMemberEditTemplate($data)."<br>
				
				<h2>Kommentare</h2>
				<div class='ui segment'>
					<textarea name='Contact-comment' rows='4'>".$data['info']->comment."</textarea>
				</div>

			</div>
			<div id='popup-footer'>

				<button type='submit' id='edit-save' name='edit' value='".$data['info']->id."' class='ui blue button icon labeled'>
					<i class='save icon'></i>
					Speichern
				</button>
				<button type='submit' id='edit-delete' name='delete' value='".$data['info']->id."' class='ui red button icon labeled'>
					<i class='delete icon'></i>
					Löschen
				</button>
				<button type='button' onclick=\"popup_close('".$dialog."');\"class='ui button icon labeled'>
					<i class='ban icon'></i>
					Abbrechen
				</button>
				<button type='button' onclick='newMemberTestData()' class='ui button'>
					Test-Daten
				</button>
			</div>
		</form>
	</div>
";


echo $html;

?>

