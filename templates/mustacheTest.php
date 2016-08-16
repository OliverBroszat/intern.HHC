<?php
/**
 * Template Name: Mustache
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// TEST CODE!

function new_paragraph($headline) {
    echo "<h1>$headline</h1>";
}

get_header();
$data = array(
	'personData' => array(
		array('description' => 'Privat', 'mail' => 'a@b.de'),
		array('description' => 'Privat2', 'mail' => 'a1@b.de'),
		array('description' => 'Privat3', 'mail' => 'a2@b.de')
	)
);
?>

<script type='text/javascript' src='http://localhost/~alex/wordpress/wp-content/themes/intern-hhc/import/js/Mustache/mustache_v2.2.1.js'></script>
<script type='text/javascript' src='http://localhost/~alex/wordpress/wp-content/themes/intern-hhc/js/expandableList.js'></script>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
	<div id='text'></div>

	<exl-container id='mails' template='name-list' source='personData' class='exl-container'></exl-container><br>

<?php
echo 'Start<br>';
$mustache = new Mustache_Engine(
	array(
		'partials' => array('memberTemplate' => 'Hallo {{name}}!<br>')
	)
);
$tmpl = "{{# members}}Template für {{name}}: {{> memberTemplate}}{{/ members}}";
$data = array(
	'members' => array (
		array('name' => 'Alex'),
		array('name' => 'Peter')
	)
);
echo $mustache->render($tmpl, $data);
echo 'End<br><br><br>';

?>

	<script>
		initializeExpandableList();
		setupExlContainerWithID('mails', <?php echo json_encode($data); ?>);
	</script>
	</div>
</div>









