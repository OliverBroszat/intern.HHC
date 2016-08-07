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
        $this->baseDataController->tryToInsertData(
            'Contact',
            array(
                'prefix' => $contactProfile->contactDatabaseRow->getValueForKey('prefix'),
                'first_name' => $contactProfile->contactDatabaseRow->getValueForKey('first_name'),
                'last_name' => $contactProfile->contactDatabaseRow->getValueForKey('last_name'),
                'birth_date' => $contactProfile->contactDatabaseRow->getValueForKey('birth_date'),
                'comment' => $contactProfile->contactDatabaseRow->getValueForKey('comment'),
                'skype_name' => $contactProfile->contactDatabaseRow->getValueForKey('skype_name')
            ),
            array('%s','%s','%s','%s','%s','%s')
        );
        $newContactId = $this->baseDataController->getIdFromLastInsert();
        $contactProfile->contactDatabaseRow->setValueForKey('id', $newContactId);
        foreach ($contactProfile->addressDatabaseRows as $addr) {
            $addr->setValueForKey('contact', $newContactId);
            $this->baseDataController->tryToInsertData(
                'Address',
                array(
                    'description' => $addr->getValueForKey('description'),
                    'street' => $addr->getValueForKey('street'),
                    'number' => $addr->getValueForKey('number'),
                    'addr_extra' => $addr->getValueForKey('addr_extra'),
                    'postal' => $addr->getValueForKey('postal'),
                    'city' => $addr->getValueForKey('city'),
                    'contact' => $addr->getValueForKey('contact')
                ),
                array('%s','%s','%d','%s','%s', '%s','%d')
            );
            $lastAddressInsertId = $this->baseDataController->getIdFromLastInsert();
            $addr->setValueForKey('id', $lastAddressInsertId);
        }
        foreach ($contactProfile->mailDatabaseRows as $mail) {
            $mail->setValueForKey('contact', $newContactId);
            $this->baseDataController->tryToInsertData(
                'Mail',
                array(
                    'description' => $mail->getValueForKey('description'),
                    'address' => $mail->getValueForKey('address'),
                    'contact' => $mail->getValueForKey('contact')
                ),
                array('%s','%s', '%d')
            );
            $lastMailInsertId = $this->baseDataController->getIdFromLastInsert();
            $mail->setValueForKey('id', $lastMailInsertId);
        }
        foreach ($contactProfile->phoneDatabaseRows as $phone) {
            $phone->setValueForKey('contact', $newContactId);
            $this->baseDataController->tryToInsertData(
                'Phone',
                array(
                    'description' => $phone->getValueForKey('description'),
                    'number' => $phone->getValueForKey('number'),
                    'contact' => $phone->getValueForKey('contact')
                ),
                array('%s','%s', '%d')
            );
            $lastPhoneInsertId = $this->baseDataController->getIdFromLastInsert();
            $phone->setValueForKey('id', $lastPhoneInsertId);
        }
        foreach ($contactProfile->studyDatabaseRows as $study) {
            $study->setValueForKey('contact', $newContactId);
            $this->baseDataController->tryToInsertData(
                'Study',
                array(
                    'contact' => $study->getValueForKey('contact'),
                    'status' => $study->getValueForKey('status'),
                    'school' => $study->getValueForKey('school'),
                    'course' => $study->getValueForKey('course'),
                    'start' => $study->getValueForKey('start'),
                    'end' => $study->getValueForKey('end'),
                    'focus' => $study->getValueForKey('focus'),
                    'degree' => $study->getValueForKey('degree'),
                ),
                array('%d','%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
            $lastStudyInsertId = $this->baseDataController->getIdFromLastInsert();
            $study->setValueForKey('id', $lastStudyInsertId);
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


















