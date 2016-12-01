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
    'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory() . '/views/edit', array('extension' => '.html')),
));

if ($id != 'new') {
	/*** EDIT Contact ***/

	// create MemberDataController
	$memberController = new MemberDataController(null, new ContactDataController(null, new BaseDataController));
	// get MemberProfile
	$memberProfile = $memberController->getSingleMemberProfileByContactID($id);

	// create Translator
	$translator = new Translator();
	// specify which tables need JSON encoding to work with EXL
	$encode = array('mails', 'phones', 'addresses', 'studies');
	
	// (I) Transform MemberProfile to data (array) used with Musatche
	$data = $translator->transformSingleMemberProfileToData($memberProfile, $encode);

	// (II) Translate raw values from MemberProfile to readable values (e.g. member.active: 0 -> 'Aktiv')
	$translatedProfile = $translator->translateSingleMemberProfile($memberProfile);
	// Transform translated MemberProfile (II) to data (array) used with Musatche
	$translatedData = $translator->transformSingleMemberProfileToData($translatedProfile, $encode);	

	// combine (I) with (II) and the template_directory 
	$data = array('data' => $data, 'translatedData' => $translatedData, 'dir' => get_template_directory_uri());
	// RENDER editMember Template (/views/edit/editMember.html) with Mustache
	$html = $mustache->render('editMember', $data);

}
else {
	/*** Create New Contact ***/
	
	// fake data for profile image
	$data = array('translatedData' => array('contact' => array('image' => array('thumbnail' => get_template_directory_uri().'/resources/images/profile_placeholder_female.png', 'source' => '#' ))));
	
	// RENDER newMember Template (/views/edit/newMember.html) with Mustache
	$html = $mustache->render('newMember', $data);
}


// return HTML
echo $html;