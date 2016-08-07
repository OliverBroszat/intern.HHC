<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.07.16
 * Time: 23:07
 */

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
 * Class BaseDataController
 *
 * Provides full CRUD functionality to HHC Contact data
 */
class ContactDataController {

    private $baseDataController;
    private $userSecurityPass;

    public function __construct($userSecurityPass) {
        //$this->ifUserNotLoggedInThrowException();
        $this->baseDataController = new BaseDataController();
    }

    public function createSingleContactFromProfile($contactProfile) {
        $this->baseDataController->tryToInsertRowWithAutoUpdateSingleAutoPrimary(
            'Contact',
            $contactProfile->contactDatabaseRow
        );
        $newContactID = $contactProfile->contactDatabaseRow->getValueForKey('id');
        $contactProfile->updateDataWithContactID($newContactID);
        $this->createContactItemsForTable('Address', $contactProfile->addressDatabaseRows);
        $this->createContactItemsForTable('Mail', $contactProfile->mailDatabaseRows);
        $this->createContactItemsForTable('Phone', $contactProfile->phoneDatabaseRows);
        $this->createContactItemsForTable('Study', $contactProfile->studyDatabaseRows);
    }

    private function createContactItemsForTable($table, $dataRows) {
        foreach ($dataRows as $row) {
            $this->baseDataController->tryToInsertRowWithAutoUpdateSingleAutoPrimary($table, $row);
        }
    }

    public function createMultipleContactProfiles($contactProfile) {
        /*
        Pro contactProfile die Methode createSingleContactProfile() aufrufen
        */
    }

    public function getSingleContactProfileByID($contactID) {
        $contactRow = $this->getContactDatabaseRowByID($contactID);
        $addressRows = $this->getContactDetailsForIDFromTable('Address', $contactID);
        $mailRows = $this->getContactDetailsForIDFromTable('Mail', $contactID);
        $phoneRows = $this->getContactDetailsForIDFromTable('Phone', $contactID);
        $studyRows = $this->getContactDetailsForIDFromTable('Study', $contactID);
        $contactProfile = new ContactProfile(
            $contactRow,
            $addressRows,
            $mailRows,
            $phoneRows,
            $studyRows
        );
        return $contactProfile;
    }

    public function getMultipleContactProfilesByID($contactIDs) {
        $contactProfiles = array();
        foreach ($contactIDs as $contactID) {
            $currentContactProfile = $this->getSingleContactProfileByID($contactID);
            array_push($contactProfiles, $currentContactProfile);
        }
        return $contactProfiles;
    }

    public function updateSingleContactProfile($contactProfile) {
        /*
        TODO: delete-create Kombination? Impliziert neue ID für den Contact!
        ansonsten einfach per 
        */
    }

    public function deleteSingleContactByID($contactID) {
        $table = 'Contact';
        $whereData = array('id' => $contactID);
        $whereFormat = null;
        $this->baseDataController->tryToDeleteData($table, $whereData, $whereFormat);
    }

    public function deleteMultipleContactsByID($contactIDs) {
        foreach ($contactIDs as $ID) {
            $this->deleteSingleContactByID($ID);
        }
    }

    private function getContactDatabaseRowByID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Contact WHERE id=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        print_r($preparedSqlQuery);
        $contactRow = $this->baseDataController->tryToSelectSingleRowByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getContactDetailsForIDFromTable($table, $contactID) {
        $unpreparedSqlQuery = "SELECT * FROM $table WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

}


















