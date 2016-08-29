<?php
/**
 * Template Name: Bewerben
 */

get_header();

$root = get_template_directory();
require_once("$root/functions/html_templates/userdata.php");

// require_once("$root/functions/kasAPI/mailinglist.php");
// $req = createMailingList('dynamisch', array('alexander.schaefer@hhc-duesseldorf.de', 'alexander.schaefer8193@gmail.com'));
// $req = getTargetsFromMailingList('mpr@hhc-duesseldorf.de');
//var_dump(explode(',',$req['Response']['ReturnInfo'][0]['mail_forward_targets']));

?>
	<div class="outer small clearfix">
		<h1>Bewerbung</h1>

			
		<form action="<?php echo get_template_directory_uri(); ?>\OOP\model\DataController\ApplicationDataController\ApplicationDataController.php" method='POST' enctype='multipart/form-data'>	

			<?php
				echo "<div class='panel'>".getContactEditTemplate(null)."</div><br>";
				echo "<div class='panel'>".getAddressEditTemplate(null)."</div><br>";
				echo "<div class='panel'>".getStudyEditTemplate(null)."</div><br>";
				echo "<div class='panel'>".getFileEditTemplate(null)."</div><br>";
			?>
			<button type='submit' class='registrieren'>Bewerbung abschicken!</button>

		</form>
	</div>
</body>

</html>