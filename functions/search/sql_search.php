<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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
	'html' => $html
);

// Return
print json_encode($return);