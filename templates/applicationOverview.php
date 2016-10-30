<?php
/**
 * Template Name: Bewerbungsübersicht
 */

get_header();

// create mustache object
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/application', array('extension' => '.html')),
));


// get application IDs
// todo: move to SearchDataController
$applicationDataModel = new ApplicationDataModel(new BaseDataController());
$applications = $applicationDataModel->getAllApplications();

$applicationIDs = array();
foreach ($applications as $application) {
	array_push($applicationIDs, $application->getValueForKey('id'));
}

// get ApplicationProfiles
$applicationProfileController = new ApplicationProfileController();
$applicationProfiles = $applicationProfileController->createMultipleApplicationProfilesByIDs($applicationIDs);


// DEBUG Output
arr_to_list($applicationProfiles);
exit();

$translator = new Translator();

$data = array('test' => 'Hallo Welt!');

// RENDER applicationOverview Template with Mustache
$html = $mustache->render('applicationOverview', $data);

// return HTML
echo $html;
