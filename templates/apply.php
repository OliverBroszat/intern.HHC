<?php
/**
 * Template Name: Bewerben
 */

get_header();

$root = get_template_directory();
require_once("$root/functions/html_templates/userdata.php");



require_once("$root/functions/kasAPI/mailinglist.php");

// $req = createMailingList('dynamisch', array('alexander.schaefer@hhc-duesseldorf.de', 'alexander.schaefer8193@gmail.com'));
$req = getTargetsFromMailingList('mpr@hhc-duesseldorf.de');
var_dump(explode(',',$req['Response']['ReturnInfo'][0]['mail_forward_targets']));




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
		<br>
		<?php echo getFileEditTemplate(null); ?>
		
		<button type='submit' class='registrieren'>Bewerbung abschicken!</button>

	</form>

</body>

</html>