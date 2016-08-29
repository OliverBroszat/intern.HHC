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

// create MemberDataController
$memberController = new MemberDataController(null, new ContactDataController(null, new BaseDataController));
// get MemberProfile
$memberProfile = $memberController->getSingleMemberProfileByContactID($id);

// create Translator
$translator = new Translator();
// Transform MemberProfile to data used with Musatche
$encode = array('mails', 'phones', 'addresses', 'studies');
$data = $translator->transformSingleMemberProfileToData($memberProfile, $encode);

// create mustache object
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/edit', array('extension' => '.html')),
));

// RENDER editMember Template with Mustache
$html = $mustache->render('editMember', $data);

// return HTML
echo $html;
