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


/**
 * Class ApplicationProfileController
 */
class ApplicationProfileController {

    private $userSecurityPass;
    private $baseDataController;
    private $contactDataController;
    private $applicationDataModel;

    public function __construct($userSecurityPass=NULL) {
        $this->userSecurityPass = $userSecurityPass;
        $this->baseDataController = new BaseDataController();
        $this->contactDataController = new ContactDataController($this->userSecurityPass, $this->baseDataController);
        $this->applicationDataModel = new ApplicationDataModel($this->baseDataController);
    }

    public function createSingleApplicationProfileByID($applicationID) {
        $applicationDatabaseRow = $this->applicationDataModel->getApplicationByID($applicationID);
        $contactProfile = $this->contactDataController->getSingleContactProfileByID($applicationDatabaseRow->getValueForKey('contact'));
        $attachmentDatabaseRows = $this->applicationDataModel->getAttachmentsForApplication($applicationID);

        $applicationProfile = new ApplicationProfile($applicationDatabaseRow, $contactProfile, $attachmentDatabaseRows);
        return $applicationProfile;
    }

    public function createMultipleApplicationProfilesByIDs($applicationIDs) {
        $applicationProfiles = array();
        foreach ($applicationIDs as $applicationID) {
           array_push($applicationProfiles, $this->createSingleApplicationProfileByID($applicationID));
        }
        return $applicationProfiles;
    }

}