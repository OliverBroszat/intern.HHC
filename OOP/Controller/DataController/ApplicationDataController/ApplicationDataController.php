<?php

/*
 * Because we're outside the wordpress template directory, no wordpress functionality will be
 * pre-includes for us :(
 * In this block we include all necessary functions and an autoloader by loading the 'wp-load.php'.
 * NOTE: on local host this file has a different path!
 */
if (!function_exists('serverIsRunningOnLocalHost')) {
    function serverIsRunningOnLocalHost() {
        $localHostAddresses = array('127.0.0.1', '::1');
        $currentServerIPAddress = $_SERVER['REMOTE_ADDR'];
        if(in_array($currentServerIPAddress, $localHostAddresses)){
            return true;
        }
        return false;
    }
}

if (!function_exists('loadWordpressFunctions')) {
    function loadWordpressFunctions() {
        $serverRootPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        if (serverIsRunningOnLocalHost()) {
            $serverRootPath = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
        }
        require_once("$serverRootPath/wp-load.php");
    }
}

loadWordpressFunctions();

//arr_to_list($post);



class ApplicationDataController {
	private $ContactDataController;
	private $ApplicationDataModel;

	public function __construct() {
		$base = new BaseDataController();
		$this->ContactDataController = new ContactDataController(null, $base);
		$this->ApplicationDataModel = new ApplicationDataModel($base);
	}

	public function createApplicationFromForm($profile) {
		$this->ContactDataController->createSingleContactByProfile($profile);
		$contactID = $profile->contactDatabaseRow->getValueForKey('id');
		$this->ApplicationDataModel->createApplicationForContact($contactID);
	}

	public function processPostToProfile($postvar) {
		$post = post_to_array($postvar);
		$profileArray = array();

		$profileData = new DatabaseRow( (object) $post['Contact'][0] );
		$mailData = array();
		foreach ($post[Mail] as $mail) {
			$currentMailRow = new DatabaseRow( (object) $mail );
			array_push($mailData, $currentMailRow);
		}

		$phoneData = array();
		foreach ($post[Phone] as $phone) {
			$currentPhoneRow = new DatabaseRow( (object) $phone );
			array_push($phoneData, $currentPhoneRow);
		}

		$addressData = array();
		foreach ($post[Address] as $address) {
			$currentAddressRow = new DatabaseRow( (object) $address );
			array_push($addressData, $currentAddressRow);
		}

		$studyData = array();
		foreach ($post[Study] as $study) {
			$currentStudyRow = new DatabaseRow( (object) $study );
			array_push($studyData, $currentStudyRow);
		}
		$myProfile = new ContactProfile(
			$profileData,
			$addressData,
			$mailData,
			$phoneData,
			$studyData
		);
		return $myProfile;
	}
}
$ApplicationDataController = new ApplicationDataController();
$myProfile = $ApplicationDataController->processPostToProfile($_POST);
$ApplicationDataController->createApplicationFromForm($myProfile);
// Mit Contact Application erstellen

// Anhänge in Datenbank speichern

// Application mit Anhängen verknüpfen

// Ergebnis anzeigen
?>