<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.07.16
 * Time: 06:50
 */

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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
        $this->contactDataController->createSingleContactByProfile($memberProfile->contactProfile);
        $memberProfile->memberDatabaseRow->setValueForKey(
            'contact',
            $memberProfile->contactProfile->contactDatabaseRow->getValueForKey('id')
        );
        $this->baseDataController->insertSingleRowInTable($memberProfile->memberDatabaseRow, 'Member');
    }

    public function createMultipleMembersByProfile($memberProfiles) {
        foreach ($memberProfiles as $memberProfile) {
            $this->createSingleMemberByProfile($memberProfile);
        }
    }

    private function createSingleMemberByDatabaseRow($memberDatabaseRow) {
        $this->baseDataController->insertSingleRowInTable($memberDatabaseRow, 'Member');
    }

    public function getSingleMemberProfileByContactID($ID) {
        $contactProfile = $this->contactDataController->getSingleContactProfileByID($ID);
        $unpreparedMemberSqlQuery = "SELECT Member.contact, Member.active, Member.position, Member.joined, Member.left, Member.ressort, Ressort.name  FROM Member INNER JOIN Ressort ON Member.ressort = Ressort.id WHERE contact=%d;";
        $preparedMemberSqlQuery = $this->baseDataController->prepareSqlQuery(
            $unpreparedMemberSqlQuery,
            $ID
        );
        $memberRow = $this->baseDataController->selectSingleRowByQuery($preparedMemberSqlQuery);
        return new MemberProfile($memberRow, $contactProfile);
    }

    public function getMultipleMemberProfilesByContactID($IDs) {
        $memberProfiles = array();
        foreach ($IDs as $ID) {
            $currentMemberProfile = $this->getSingleMemberProfileByContactID($ID);
            array_push($memberProfiles, $currentMemberProfile);
        }
        return $memberProfiles;
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

    public function getRessortDatabaseRowForMember($memberProfile) {
        $ressortID = $memberProfile->memberDatabaseRow->getValueForKey('ressort');
        $ressortDatabaseRow = $baseDataController->selectSingleRowByIDInTable($ressortID, 'Ressort');
        return $ressortDatabaseRow;
    }

    public function updateSingleMemberProfile($memberProfile) {
        $this->contactDataController->updateSingleContactProfile($memberProfile->contactProfile);
        try {
            $this->baseDataController->updateSingleRowInTable($memberProfile->memberDatabaseRow, 'Member');
        }
        catch (InvalidArgumentException $e) {
            // No rows where updated - ignore this case
        }
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












