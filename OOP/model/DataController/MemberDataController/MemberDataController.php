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
if (! function_exists ( 'serverIsRunningOnLocalHost' )) {
	function serverIsRunningOnLocalHost() {
		$localHostAddresses = array (
				'127.0.0.1',
				'::1' 
		);
		$currentServerIPAddress = $_SERVER ['REMOTE_ADDR'];
		if (in_array ( $currentServerIPAddress, $localHostAddresses )) {
			return true;
		}
		return false;
	}
}

if (! function_exists ( 'loadWordpressFunctions' )) {
	function loadWordpressFunctions() {
		$serverRootPath = realpath ( $_SERVER ["DOCUMENT_ROOT"] );
		if (serverIsRunningOnLocalHost ()) {
			$serverRootPath = realpath ( $_SERVER ["CONTEXT_DOCUMENT_ROOT"] ) . '/wordpress';
		}
		require_once ("$serverRootPath/wp-load.php");
	}
}

loadWordpressFunctions ();

/**
 * Class MemberDataController
 *
 * Description goes here
 */
class MemberDataController {
	private $baseDataController;
	private $contactDataController;
	private $userSecurityPass;
	public function __construct($securityPass, $contactDataController) {
		$this->userSecurityPass = $securityPass;
		// $this->ifUserNotLoggedInThrowException();
		$this->contactDataController = $contactDataController;
		$this->baseDataController = $contactDataController->getBaseDataController ();
	}
	public function createSingleMemberByProfile($memberProfile) {
		$this->contactDataController->createSingleContactByProfile ( $memberProfile->contactProfile );
		$memberProfile->memberDatabaseRow->setValueForKey ( 'contact', $memberProfile->contactProfile->contactDatabaseRow->getValueForKey ( 'id' ) );
		$this->baseDataController->insertSingleRowInTable ( $memberProfile->memberDatabaseRow, 'Member' );
	}
	public function createMultipleMembersByProfile($memberProfiles) {
		foreach ( $memberProfiles as $memberProfile ) {
			$this->createSingleMemberByProfile ( $memberProfile );
		}
	}
	private function createSingleMemberByDatabaseRow($memberDatabaseRow) {
		$this->baseDataController->insertSingleRowInTable ( $memberDatabaseRow, 'Member' );
	}
	public function getSingleMemberProfileByContactID($ID) {
		$contactProfile = $this->contactDataController->getSingleContactProfileByID ( $ID );
		$unpreparedMemberSqlQuery = "SELECT * FROM Member WHERE contact=%d;";
		$preparedMemberSqlQuery = $this->baseDataController->prepareSqlQuery ( $unpreparedMemberSqlQuery, $ID );
		$memberRow = $this->baseDataController->selectSingleRowByQuery ( $preparedMemberSqlQuery );
		return new MemberProfile ( $memberRow, $contactProfile );
	}
	public function getMultipleMemberProfilesByContactID($IDs) {
		foreach ( $IDs as $ID ) {
			$this->getSingleMemberProfileByID ( $ID );
		}
	}
	public function getCurrentMemberProfile() {
		// getWPID
		$wordpressID = $this->userSecurityPass->getCurrentUserID ();
		// MatchWithInternID
		
		if (is_null ( $wordpressID )) {
			$errorMessage = "wordpressID was not set in BaseDataController::setSingleRowByIDInTable";
			throw new ValueNotSetException ( $errorMessage );
		} else {
			$query = "SELECT contact from member where wordpressID = '$wordpressID'";
			
			$result = $this->baseDataController->selectSingleRowByQuery ( $query );
			$result = $result->getValueForKey ( 'contact' );
			
			if(!is_null($result)){
			// GetUserByID
			$user = $this->getSingleMemberProfileByContactID ( $result );
			return $this->getSingleMemberProfileByContactID ( $result );
			} else {
				$errormessage = "contact was not set in MemberDataController::getCurrentMemberProfile";
				throw new LengthException($errorMessage);
			}
		}
	}
	public function getAllMemberProfiles() {
		//
	}
	public function getMemberProfilesByFilter($filter) {
		// TODO: implement filter objects!
	}
	public function getRessortDatabaseRowForMember($memberProfile) {
		$ressortID = $memberProfile->memberDatabaseRow->getValueForKey ( 'ressort' );
		$ressortDatabaseRow = $this->baseDataController->selectSingleRowByIDInTable ( $ressortID, 'Ressort' );
		return $ressortDatabaseRow;
	}
	
	public function getPositionDatabaseRowForMember($memberprofile){
		$position = $memberprofile->memberDatabaseRow->getValueForKey('position');
		return $position;
	}
	
	public function updateSingleMemberProfile($memberProfile) {
		$this->contactDataController->updateSingleContactProfile ( $memberProfile->contactProfile );
		$this->baseDataController->updateSingleRowInTable ( $memberProfile->memberDatabaseRow, 'Member' );
	}
	public function updateMultipleMembersByProfile($memberProfiles) {
		foreach ( $memberProfiles as $memberProfile ) {
			$this->updateSingleMemberByProfile ( $memberProfile );
		}
	}
	public function deleteSingleMemberByID($id) {
		$this->contactDataController->deleteSingleContactByID ( $id );
	}
	public function deleteMultipleMembersByID($IDs) {
		foreach ( $IDs as $ID ) {
			$this->deleteSingleMemberByID ( $ID );
		}
	}
	public function deleteSingleMemberByProfile($memberProfile) {
		$this->contactDataController->deleteSingleContactByProfile ( $memberProfile->contactProfile );
	}
	public function deleteMultipleMembersByProfile($memberProfiles) {
		foreach ( $memberProfiles as $memberProfile ) {
			$this->deleteSingleMemberByProfile ( $memberProfile );
		}
	}
}












