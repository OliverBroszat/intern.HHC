<?php 
/* 
	createHTML.php 

	Nimmt die fertigen Daten von postProcess entgegen und generiert HMTL Code, welcher dann per echo ausgegeben werden kann.
*/

// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 

require_once("$root/wp-load.php");

$root = get_template_directory();

	
require_once("$root/functions/html_templates/userdata.php");

function createHTML($data){

	$dialog = "Wollen Sie das Fenster wirklich ohne zu speichern schließen? Ungespeicherte Änderungen gehen verloren.";

	$html = "
		<div id='edit'>
			<form method='POST' action='".get_template_directory_uri()."/functions/edit/sql_edit.php' enctype='multipart/form-data'>
				<h2>Eintrag bearbeiten</h2>
				<div id='popup-content'>

					<h2>Profilbild</h2>
					<div class='edit-image clearfix'>
						<div class='edit-image-image'>
							".$data['image']."
						</div>
						<div class='edit-image-buttons'>
							<input type='file' class='full-width' id='edit-upload-image' placeholder='Upload' name='upload-image'><br>
							<button type='button' class='full-width' id='edit-delete-image' style='display:none;'>Löschen</button><br>
						</div>						
					</div>

					".getContactEditTemplate($data)."<br>
					".getAddressEditTemplate($data)."<br>
					".getStudyEditTemplate($data)."<br>
					".getMemberEditTemplate($data)."

				</div>
				<div id='popup-footer'>
					<button type='submit' name='id' value='".$data['info']->id."'> Speichern </button> 
					<button type='button' onclick=\"popup_close('".$dialog."');\"> Abbrechen </button> 
				</div>
			</form>
		</div>
	
	";
		
	return $html;
}

?>