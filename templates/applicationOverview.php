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

// transform ApplicationProfiles to data (array) usable with mustache
$translator = new Translator();
$applications = $translator->transformMultipleApplicationProfilesToData($applicationProfiles);

$numberOfApplications = sizeof($applications);

$sort_categories = array(
	array('value' => 'Contact.last_name', 'name' => 'Nachname'),
	array('value' => 'Contact.first_name', 'name' => 'Vorname'),
	array('value' => 'Application.state', 'name' => 'State'),
	array('value' => 'Application.income', 'name' => 'Income'),
);


// Filter-Data
$filters = new FilterLists();
$filtersHTML = array();
foreach ($filters->getApplicationFilter() as $filter) {
	array_push($filtersHTML, $filter->createHtmlTable());
}

$data = array(
	'applications' => $applications,
	'numberOfApplications' => $numberOfApplications,
	'root_path' => get_template_directory_uri(),
	'sort_categories' => $sort_categories,
	'filters' => $filtersHTML,

);

// RENDER applicationOverview Template with Mustache
$html = $mustache->render('applicationOverview', $data);

// return HTML
echo $html;
