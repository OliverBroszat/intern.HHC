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

    private $contactDataController;
    private $userSecurityPass;

    public function __construct($userSecurityPass) {
        $this->userSecurityPass = $userSecurityPass;
        //$this->ifUserNotLoggedInThrowException();
        $this->contactDataController = new ContactDataController($userSecurityPass);
    }

    public function createSingleMemberByProfile($memberProfile) {
        //
    }

    public function createMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->createSingleMemberByProfile($memberProfile);
        }
    }

    public function getSingleMemberProfileByID($ID) {
        //
    }

    public function getMultipleMemberProfilesByID($IDs) {
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

    public function updateSingleMemberByProfile($memberProfile) {
        //
    }

    public function updateMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->updateSingleMemberByProfile($memberProfile);
        }
    }

    public function deleteSingleMemberByID($id) {
        //
    }

    public function deleteMultipleMembersByID($IDs) {
        foreach ($IDs as $ID) {
            $this->deleteSingleMemberByID($ID);
        }
    }

    public function deleteSingleMemberByProfile($memberProfile) {
        //
    }

    public function deleteMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->deleteSingleMemberByProfile($memberProfile);
        }
    }

}












