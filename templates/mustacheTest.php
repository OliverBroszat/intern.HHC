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

<script type='text/javascript' src='http://localhost/~alex/wordpress/wp-content/themes/intern-hhc/import/js/Mustache/mustache_v2.2.1.js'></script>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
	<div id='text'></div>
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

<script>
$.get('template.mst', function(template) {
	var rendered = Mustache.render(template, {name: "Luke"});
	$('#target').html(rendered);
});

var view = {
  title: "Joe",
  calc: function () {
    return 2 + 4;
  }
};

var output = Mustache.render("{{title}} spends {{calc}}", view);
console.log(output);
document.getElementById('text').innerHTML = output;
</script>












