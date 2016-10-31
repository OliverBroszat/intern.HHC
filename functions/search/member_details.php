<?php

/*
	Diese Datei wird über AJAX (/js/ajax_edit.js) aufgerufen, fragt zu einer ID die Daten ab und gibt das Ergebnis in der gewünschten Form zurück.
*/

// Load WP-Functions
$localhost = array('127.0.0.1', '::1');
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
}
require_once("$root/wp-load.php");

// get ID
$id = $_POST['id'];

// create mustache object
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/search', array('extension' => '.html')),
));


/*** SHOW MEMBER DETAILS ***/

// create MemberDataController
$memberController = new MemberDataController(null, new ContactDataController(null, new BaseDataController));
// get MemberProfile
$memberProfile = $memberController->getSingleMemberProfileByContactID($id);

// Prepare Data for Mustache
$translator = new Translator();
$translatedProfile = $translator->translateSingleMemberProfile($memberProfile);
$data = $translator->transformSingleMemberProfileToData($translatedProfile);

// RENDER with Mustache
$html = $mustache->render('memberDetails', $data);

// return HTML
echo $html;