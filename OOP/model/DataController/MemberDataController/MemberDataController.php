<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.07.16
 * Time: 06:50
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
 * Class MemberDataController
 *
 * Description goes here
 */
class MemberDataController {

    private $baseDataController;
    private $contactDataController;
    private $userSecurityPass;

    public function __construct($userSecurityPass, $contactDataController) {
        $this->userSecurityPass = $userSecurityPass;
        //$this->ifUserNotLoggedInThrowException();
        $this->contactDataController = $contactDataController;
        $this->baseDataController = $contactDataController->getBaseDataController();
    }

    public function createSingleMemberByProfile($memberProfile) {
        print_r('*********ERSTELLE CONTACT');
        $this->contactDataController->createSingleContactByProfile($memberProfile->contactProfile);
        $memberProfile->memberDatabaseRow->setValueForKey(
            'contact',
            $memberProfile->contactProfile->contactDatabaseRow->getValueForKey('id')
        );
        print_r('*********ERSTELLE MEMBER');
        echo '<br><br>';
        var_dump($memberProfile->memberDatabaseRow);
        echo '<br><br>';
        $this->createMemberByDatabaseRow($memberProfile->memberDatabaseRow);
        print_r('*********ERSTELLE DONE');
    }

    public function createSingleMemberByProfileWithFixedID($id, $memberProfile) {
        print_r('*********ERSTELLE CONTACT');
        $this->contactDataController->createSingleContactByProfileWithID($id, $memberProfile->contactProfile);
        echo '$$$$$$$$$$$$$$$$$$$$$$$$';
        $memberProfile->memberDatabaseRow->setValueForKey(
            'contact',
            $memberProfile->contactProfile->contactDatabaseRow->getValueForKey('id')
        );
        print_r('*********ERSTELLE MEMBER');
        echo '<br><br>';
        var_dump($memberProfile->memberDatabaseRow);
        echo '<br><br>';
        $this->createMemberByDatabaseRow($memberProfile->memberDatabaseRow);
        print_r('*********ERSTELLE DONE');
    }

    public function createMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->createSingleMemberByProfile($memberProfile);
        }
    }

    private function createMemberByDatabaseRow($memberDatabaseRow) {
        $this->baseDataController->tryToInsertRow('Member', $memberDatabaseRow);
    }

    public function getSingleMemberProfileByContactID($ID) {
        $contactProfile = $this->contactDataController->getSingleContactProfileByID($ID);
        $unpreparedMemberSqlQuery = "SELECT * FROM Member WHERE contact=%d;";
        $preparedMemberSqlQuery = $this->baseDataController->prepareSqlQuery(
            $unpreparedMemberSqlQuery,
            $ID
        );
        $memberRow = $this->baseDataController->tryToSelectSingleRowByQuery($preparedMemberSqlQuery);
        return new MemberProfile($memberRow, $contactProfile);
    }

    public function getMultipleMemberProfilesByContactID($IDs) {
        foreach ($IDs as $ID) {
            $this->getSingleMemberProfileByID($ID);
        }
    }

    public function getCurrentMemberProfile() {
        //
    }

    public function getAllMemberProfiles() {
        //
    }

    public function getMemberProfilesByFilter($filter) {
        // TODO: implement filter objects!
    }

    public function updateSingleMemberByProfileWithID($id, $memberProfile) {
        $this->deleteSingleMemberByID($id);
        $this->createSingleMemberByProfileWithFixedID(
            $id,
            $memberProfile
        );
    }

    public function updateMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->updateSingleMemberByProfile($memberProfile);
        }
    }

    public function deleteSingleMemberByID($id) {
        $this->contactDataController->deleteSingleContactByID($id);
    }

    public function deleteMultipleMembersByID($IDs) {
        foreach ($IDs as $ID) {
            $this->deleteSingleMemberByID($ID);
        }
    }

    public function deleteSingleMemberByProfile($memberProfile) {
        $this->contactDataController->deleteSingleContactByProfile($memberProfile->contactProfile);
    }

    public function deleteMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->deleteSingleMemberByProfile($memberProfile);
        }
    }

}












