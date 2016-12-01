<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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