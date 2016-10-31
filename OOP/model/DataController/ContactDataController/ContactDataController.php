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
    public static $tableNameToAttributeNameTranslation = array(
        'Address' => 'addressDatabaseRows',
        'Mail' => 'mailDatabaseRows',
        'Phone' => 'phoneDatabaseRows',
        'Study' => 'studyDatabaseRows'
    );


    public function __construct($userSecurityPass, $baseDataController) {
        //$userSecurityPass->ifUserNotLoggedInThrowException();
        $this->baseDataController = $baseDataController;
    }

    /*
    Wenn ein contactProfile erstellt werden soll, werden evtl. gespeicherte IDs absolut nicht beachtet und alle erstellten IDs
    werden in das ContactProfile geschrieben
    */
    public function createSingleContactByProfile($contactProfile) {
        $this->baseDataController->insertSingleRowWithAutoUpdateSingleAutoPrimaryInTable(
            $contactProfile->contactDatabaseRow,
            'Contact'
        );
        $newContactID = $contactProfile->contactDatabaseRow->getValueForKey('id');
        $contactProfile->updateDataWithContactID($newContactID);
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($contactProfile->addressDatabaseRows, 'Address');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($contactProfile->mailDatabaseRows, 'Mail');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($contactProfile->phoneDatabaseRows, 'Phone');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($contactProfile->studyDatabaseRows, 'Study');
    }

    public function createMultipleContactProfiles($contactProfiles) {
        foreach ($contactProfiles as $profile) {
            $this->createSingleContactByProfile($profile);
        }
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

    /**
    * updateSingleContactProfile
    * 
    * Performs a deletion and insertion of a given contact profile.
    * NOTE: the profile's id WILL be changed after any update
    */
    public function updateSingleContactProfile($contactProfile) {
        echo 'In updateSingleContact<br>';
        try {
            $this->baseDataController->updateSingleRowInTable($contactProfile->contactDatabaseRow, 'Contact');
        }
        catch (InvalidArgumentException $e) {
            // Nothing updated - ingore this case
        }
        echo 'Contact geupdated<br>';
        $this->updateContactProfileForTable($contactProfile, 'Address');
        echo 'Adresse geupdated<br>';
        $this->updateContactProfileForTable($contactProfile, 'Mail');
        echo 'Mail geupdated<br>';
        $this->updateContactProfileForTable($contactProfile, 'Phone');
        echo 'Phone geupdated<br>';
        $this->updateContactProfileForTable($contactProfile, 'Study');
        echo 'Study geupdated<br>';
        echo 'Update durch<br>';
    }

    private function updateContactProfileForTable($contactProfile, $table) {
        // Delete rows that dont appear in profile anymore
        $rowsToDelete = $this->getRowsToDeleteFromContactProfileForTable($contactProfile, $table);
        $this->baseDataController->deleteMultipleRowsFromTable($rowsToDelete, $table);
        // Update rows that where already in database and still remain in profile
        $rowsToUpdate = $this->getRowsToUpdateFromContactProfileForTable($contactProfile, $table);
        $this->baseDataController->updateMultipleRowsInTable($rowsToUpdate, $table);
        // Create new Rows
        $newRowsToCreate = $this->getRowsToCreateFromContactProfileForTable($contactProfile, $table);
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($newRowsToCreate, $table);
    }

    // Filtert alle Rows für ein gegebenes Attribut (Adress, Mail, ...) nach leeren IDs
    // Da diese Rows noch keine ID haben, MÜSSEN sie noch eingefügt werden
    private function getRowsToCreateFromContactProfileForTable($contactProfile, $table) {
        $rowArrayAttributeName = ContactDataController::$tableNameToAttributeNameTranslation[$table];
        $rowsInProfile = $contactProfile->$rowArrayAttributeName;
        $createFilterFunction = function ($row) {
            return ($row->getValueForKey('id') == '');
        };
        $createRows = array_filter($rowsInProfile, $createFilterFunction);
        return $createRows;
    }

    private function getRowsToUpdateFromContactProfileForTable($contactProfile, $table) {
        // Get IDs from database and profile
        $rowArrayAttributeName = ContactDataController::$tableNameToAttributeNameTranslation[$table];
        $rowsInProfile = $contactProfile->$rowArrayAttributeName;
        $IDsInProfile = DatabaseRow::filterValuesFromRowsForSingleKey('id',$rowsInProfile);
        $IDsInDatabase = $this->getCurrentlyExistingIDsForContactInTable($contactProfile, $table);
        $updateIDs = array_intersect($IDsInDatabase, $IDsInProfile);
        $updateFilterFunction = function ($row) use ($updateIDs) {
            return in_array($row->getValueForKey('id'), $updateIDs);
        };
        $updateRows = array_filter($rowsInProfile, $updateFilterFunction);
        return $updateRows;
    }

    private function getRowsToDeleteFromContactProfileForTable($contactProfile, $table) {
        // Get IDs from database and profile
        $rowArrayAttributeName = ContactDataController::$tableNameToAttributeNameTranslation[$table];
        $rowsInProfile = $contactProfile->$rowArrayAttributeName;
        $IDsInProfile = DatabaseRow::filterValuesFromRowsForSingleKey('id',$rowsInProfile);
        $IDsInDatabase = $this->getCurrentlyExistingIDsForContactInTable($contactProfile, $table);
        $deleteIDs = array_diff($IDsInDatabase, $IDsInProfile);
        $deleteRows = $this->baseDataController->selectMultipleRowsByIDInTable($deleteIDs, $table);
        return $deleteRows;
    }

    private function getCurrentlyExistingIDsForContactInTable($contactProfile, $table) {
        $contactID = $contactProfile->contactDatabaseRow->getValueForKey('id');
        $sqlQuery = "SELECT id FROM $table WHERE contact=$contactID;";
        $currentlyExistingIDs = DatabaseRow::filterValuesFromRowsForSingleKey(
            'id',
            $this->baseDataController->selectMultipleRowsByQuery($sqlQuery)
        );
        return $currentlyExistingIDs;
    }

    public function updateMultipleContactProfiles($contactProfiles) {
        foreach ($contactProfiles as $profile) {
            $this->updateSingleContactProfile($profile);
        }
    }

    public function deleteSingleContactByID($contactID) {
        $this->baseDataController->deleteSingleRowFromTableByID('Contact', $contactID);
    }

    public function deleteMultipleContactsByID($contactIDs) {
        foreach ($contactIDs as $ID) {
            $this->deleteSingleContactByID($ID);
        }
    }

    public function deleteSingleContactByProfile($contactProfile) {
        $contactID = $contactProfile->contactDatabaseRow->getValueForKey('id');
        $this->deleteSingleContactByID($contactID);
    }

    public function deleteMultipleContactsByProfile($contactProfiles) {
        foreach ($contactProfiles as $profile) {
            $this->deleteSingleContactByProfile($profile);
        }
    }

    public function getBaseDataController() {
        return $this->baseDataController;
    }

    private function getContactDatabaseRowByID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Contact WHERE id=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->selectSingleRowByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getContactDetailsForIDFromTable($table, $contactID) {
        $unpreparedSqlQuery = "SELECT * FROM $table WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->selectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

}


















