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

    public function createSingleContactProfile($contactProfile) {
        /*
        TODO: SQL Statements vorbereiten
        Frage: wie bekommt man alle SQL Querys atomar durch?
        Beispiel: Contact ist angelegt, Adressen sind angelegt, Fehler bei Mails. Dann wird abgebrochen, aber der Contact und die Mails sind noch da
        */
    }

    public function createMultipleContactProfiles($contactProfile) {
        /*
        Pro contactProfile die Methode createSingleContactProfile() aufrufen
        */
    }

    public function getSingleContactProfileByID($contactID) {
        $contactRow = $this->getContactDatabaseRowFromID($contactID);
        $addressRows = $this->getAddressDatabaseRowsFromID($contactID);
        $mailRows = $this->getMailDatabaseRowsFromID($contactID);
        $phoneRows = $this->getPhoneDatabaseRowsFromID($contactID);
        $studyRows = $this->getStudyDatabaseRowsFromID($contactID);
        $contactProfile = new ContactProfile(
            $contactRow,
            $addressRows,
            $mailRows,
            $phoneRows,
            $studyRows
        );
        return $contactProfile;
    }

    public function getMultipleContactProfilesByIDs($contactIDs) {
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

    private function getContactDatabaseRowFromID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Contact WHERE id=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectSingleRowByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getAddressDatabaseRowsFromID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Address WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getMailDatabaseRowsFromID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Mail WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getPhoneDatabaseRowsFromID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Phone WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

    private function getStudyDatabaseRowsFromID($contactID) {
        $unpreparedSqlQuery = "SELECT * FROM Study WHERE contact=%d";
        $preparedSqlQuery = $this->baseDataController->prepareSqlQuery($unpreparedSqlQuery, $contactID);
        $contactRow = $this->baseDataController->tryToSelectMultipleRowsByQuery($preparedSqlQuery);
        return $contactRow;
    }

}


















