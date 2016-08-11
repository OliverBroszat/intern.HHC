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

?>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
<?php

$options =  array('extension' => '.html');
$root = $root = get_template_directory();
$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader($root . '/views', $options),
));
echo $m->render('test', array('name' => 'Peter'));
?>

	</div>
</div>













