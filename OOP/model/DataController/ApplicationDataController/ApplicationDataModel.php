<?php
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

class ApplicationDataModel {
    private $baseDataController;

    function __construct($baseDataController) {
    	$this->baseDataController = $baseDataController;
    }

    // neue eingehende Bewerbung erstellen
    public function createApplicationForContact($contactID) {
    	$contactData = $this->baseDataController->selectSingleRowByIDInTable($contactID, 'contact');
        $newRow = new DatabaseRow((object)array('contact' => $contactData->getvalueforkey('id'), 'income' => date('Y-m-d'), 'state' => 'new', 'assessment_template' => 1));
    	$this->baseDataController->insertSingleRowInTable($newRow, "application");      
    }

    // Status einer Bewerbung verändern
    public function setStateForApplication($applicationID, $newState) {
    	$updRow = $this->baseDataController->selectSingleRowByIDInTable($applicationID, 'application');
    	$updRow['state'] = $newState;
    	$this->baseDataController->updateSingleRowInTable($updRow, 'application');
    }

     // vorhandene Bewerbung mit ihrer ID holen
    public function getApplicationByID($applicationID) {
        return $this->baseDataController->selectSingleRowByQuery("SELECT * FROM `application` WHERE `id` = $applicationID;");
    }

    // vorhandene Bewerbung zu einem Kontakt holen
    public function getApplicationByContact($contactID) {
        // return $this->baseDataController->selectSingleRowByIDInTable($contactID, 'application');
    	return $this->baseDataController->selectSingleRowByQuery("SELECT * FROM `application` WHERE `contact` = $contactID;");
    }

    public function getAllApplications(){
        return $this->baseDataController->selectMultipleRowsByQuery("SELECT * FROM `application`");
    }

    public function getAllApplicationsWithStates($states){
        $sql = '';
        if (!empty($states)) {
          foreach ($staest as $state) {
               $sql .= "`state` = `$state` OR ";
            }
            $sql = rtrim($sql, ' OR ');
        }
        else {
            $sql = 'true';
        }

        return $this->baseDataController->selectMultipleRowsByQuery("SELECT * FROM `application` WHERE $sql");
    }

    // Anlage mit Bewerbung verbinden
    public function addSingleAttachmentToApplication($applicationID, $attachmentID, $description) {
    	$newRow =new DatabaseROw((object)array(
            'applicationID' => $applicationID, 
            'attachmentID' => $attachmentID, 
            'description' => $description, ));
    	$this->baseDataController->insertSingleRowInTable($newRow, 'applicationattachment');
    }

    // mehrere Anlagen zu einer Bewerbung hinzufügen
    public function addMultipleAttachmentsToApplication($applicationID, $attachmentIDs, $descriptions) {
        for ($i=0; $i < sizeOf($attachmentIDs); $i++) { 
            $this->addSingleAttachmentToApplication($applicationID, $attachmentIDs[$i], $descriptions[$i]);
        }
    }

    public function getAttachmentsForApplication($applicationID) {
        $attachments = $this->baseDataController->selectMultipleRowsByQuery("SELECT `attachmentID`, `description` FROM `applicationattachment` WHERE `applicationID` = $applicationID;");
       
        $files = array();
        for ($i=0; $i < sizeof($attachments); $i++) { 
            $files[$i] = array(
                'description' => $attachments[$i]->getValueForKey('description'),
                'url' => get_the_guid($attachments[$i]->getValueForKey('attachmentID'))
            );
        }          
        return $files;
    }
}