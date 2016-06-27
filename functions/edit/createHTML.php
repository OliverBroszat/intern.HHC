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
	echo "
		<h2>Profilbild</h2>
		<div class='edit-image clearfix'>
			".$data['image']."
			<div class='edit-image-buttons'>
				<button class='full-width'>Hochladen</button><br>
				<button class='full-width'>Löschen</button>
				<input type=&quot;text&quot; class=&quot;media-input&quot; /><button class=&quot;media-button&quot;>Select image</button>
			</div>
		</div>
	";
		
	echo getContactEditTemplate($data).'<br>';
	echo getAddressEditTemplate($data).'<br>';
	echo getStudyEditTemplate($data);
}

?>