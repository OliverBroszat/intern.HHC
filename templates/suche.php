<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();
$root_uri = get_template_directory_uri();
$viewpath = $root_uri.'/views';

$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(
    	$viewpath,
    	array('extension' => '.html')
    )
));

echo $mustache->render('member_search', new SucheModel());

?>