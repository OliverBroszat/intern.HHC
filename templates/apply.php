<?php
/**
 * Template Name: Bewerben
 */

get_header();

$root = get_template_directory();
require_once("$root/functions/html_templates/userdata.php");

?>

	<h1>Bewerbung</h1>

		
	<form action="<?php echo get_template_directory_uri(); ?>/functions/apply/sql_apply.php" method='POST' enctype='multipart/form-data'>	

		<?php 
			// require_once("$root/functions/suchfunktion/prepareSQL.php");
			// $queries = prepareSQL('192');
			// require_once("$root/functions/suchfunktion/getData.php");
			// $data = getData($queries)['192'];

			// arr_to_list($data);

			echo getContactEditTemplate(null).'<br>';
			echo getAddressEditTemplate(null).'<br>';
			echo getStudyEditTemplate(null).'<br>';
			echo getFileEditTemplate(null).'<br>'; 

		?>		
		<button type='submit' class='registrieren'>Bewerbung abschicken!</button>

	</form>

</body>

</html>