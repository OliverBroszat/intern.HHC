<?php
/**
 * Template Name: Bewerbungsübersicht
 */

get_header();

// create mustache object
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/application', array('extension' => '.html')),
));

$data = array('test' => 'Hallo Welt!');

// RENDER applicationOverview Template with Mustache
$html = $mustache->render('applicationOverview', $data);

// return HTML
echo $html;
