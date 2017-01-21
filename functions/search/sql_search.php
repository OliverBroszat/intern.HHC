<?php

// Load WP-Functions
$localhost = array('127.0.0.1', '::1');
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
}
require_once("$root/wp-load.php");


// create searchController with $_POST-Data
$searchController = new searchController($_POST);
// Get MemberProfiles
$memberProfiles = $searchController->search();

// Prepare Data for Mustache
$translator = new Translator();
$translatedProfiles = $translator->translateMultipleMemberProfiles($memberProfiles);
$data = $translator->transformMultipleMemberProfilesToData($translatedProfiles);

// create mustache object
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/search', array('extension' => '.html')),
));

// RENDER with Mustache
$html = $mustache->render('searchResults', $data);

// Prepare Return
$return = array(
	'number' => sizeof($memberProfiles),
	'html' => $html,
	'data' => json_encode($data)
);

// Return
print json_encode($return);