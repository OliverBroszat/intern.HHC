<?php

/*
	Diese Datei wird über AJAX (/js/ajax_edit.js) aufgerufen, fragt zu einer ID die Daten ab und gibt das Ergebnis in der gewünschten Form zurück.
*/

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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