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
    	$newRow = new DatabaseRow((object)array('contact' => $contactData->getvalueforkey('id'), 'income' => date('d/m/Y'), 'state' => 'new', 'assessment_template' => 1));
    	$this->baseDataController->insertSingleRowInTable($newRow, "application");
    }

    // Status einer Bewerbung verändern
    public function setStateForApplication($applicationID, $newState) {
    	$updRow = $baseDataController->selectSingleRowByIDInTable($applicationID, 'application');
    	$updRow['state'] = $newState;
    	$baseDataController->updateSingleRowInTable($updRow, 'application');
    }

    // vorhandene Bewerbung zu einem Kontakt holen
    public function getApplicationByContact($contactID) {
    	return $baseDataController->selectSingleRowByIDInTable($contactID, 'application');
    }

    // Anlage mit Bewerbung verbinden
    public function addSingleAttachmentToApplication($applicationID, $attachmentID) {
    	$newRow =new DatabaseROw((object)array('applicationID' => $applicationID, 'attachmentID' => $attachmentID));
    	$this->baseDataController->insertSingleRowInTable($newRow, 'applicationattachment');
    }

    // mehrere Anlagen zu einer Bewerbung hinzufügen
    public function addMultipleAttachmentsToApplication($applicationID, $attachmentIDs) {
    	foreach($attachmentIDs as $attachmentID) {
    		$this->addSingleAttachmentToApplication($applicationID, $attachmentID);
    	}
    }
}

?>