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

// create mustache object
$options =  array('extension' => '.php');
$root = $root = get_template_directory();
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader($root . '/views', $options),
));


$translator = new Translator();


// Prepare Data Array
$data = array();
foreach ($memberProfiles as $number => $memberProfile) {
	
	// translate Data from db-entry to formated string
	$memberProfile =  $translator->translateMemberProfile($memberProfile);


	$addresses = array();
	foreach ($memberProfile->contactProfile->addressDatabaseRows as $key => $value) {
		array_push($addresses, $value->toArray());
	}

	$mails = array();
	foreach ($memberProfile->contactProfile->mailDatabaseRows as $key => $value) {
		array_push($mails, $value->toArray());
	}

	$phones = array();
	foreach ($memberProfile->contactProfile->phoneDatabaseRows as $key => $value) {
		array_push($phones, $value->toArray());
	}

	$studies = array();
	foreach ($memberProfile->contactProfile->studyDatabaseRows as $key => $value) {
		array_push($studies, $value->toArray());
	}

	// organize Data for single Member
	$member = array(
		'number' => $number + 1,
		'member' => $memberProfile->memberDatabaseRow->toArray(),
		'contact' => $memberProfile->contactProfile->contactDatabaseRow->toArray(),
		'addresses' => $addresses,
		'mails' => $mails,
		'phones' => $phones,
		'studies' => $studies
	);
	array_push($data, $member);
}

// RENDER
$html = $mustache->render('memberProfileTemplate', $data);

$number = sizeof($memberProfiles);

$return = array(
	'number' => $number,
	'html' => $html
);

print json_encode($return);