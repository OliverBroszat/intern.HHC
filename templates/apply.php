<?php
/**
 * Template Name: Bewerben
 */

get_header();
// <script src='https://www.google.com/recaptcha/api.js'></script> // Head Script Import für Recaptcha

$root = get_template_directory();
require_once("$root/functions/html_templates/userdata.php");
?>
<!-- Radio Buttons werden für dieses Formular ein bisschen schöner gemacht :) -->
<style>
input[type="radio"] { margin-bottom: 10px; }
td {
	vertical-align: top;
}
</style>

<style type='text/css'></style>

	<h1>Bewerbung</h1>

	<form action="<?php echo get_template_directory_uri(); ?>/functions/apply/sql_apply.php" method='POST' enctype='multipart/form-data'>	
		
		<?php echo getContactEditTemplate(null); ?>
		<br>
		<?php echo getAddressEditTemplate(null); ?>
		<br>
		<?php echo getStudyEditTemplate(null); ?>
<<<<<<< HEAD

		<!--<div class="g-recaptcha" data-sitekey="6LfSgyMTAAAAABEiRTJfFR_z6YiWBxwTr9rD9iZy"></div> 
			Element für Recaptcha-->
=======
		<br>
		<?php echo getFileEditTemplate(null); ?>
>>>>>>> Oliver_Zeugnis
		
		<button type='submit' class='registrieren'>Bewerbung abschicken!</button>
	</form>
</body>
</html>