<?php

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
class RoleDataController {
	private $baseDataController;
	function __construct() {
		$this->baseDataController = new BaseDataController ();
	}
	function getHierarchyIDByRessortname($hierarchy) {
		$id = array (
				"vorstand" => 4,
				"ressortleiter" => 3,
				"mitglied" => 2,
				"anwärter" => 1,
				"" => 1 
		);
		
		return $id [$hierarchy];
	}
	
	function getUserHierarchyIDByRessortname() {
		$securityPass = new UserSecurityPass ();
		$baseDataController = new BaseDataController ();
		$contactDataController = new ContactDataController ( $securityPass, $baseDataController );
		$memberDataController = new MemberDataController ( $securityPass, $contactDataController );
		
		$memberProfile = $memberDataController->getCurrentMemberProfile();
		
		
			$memberDatabaseRow = $memberDataController->getRessortDatabaseRowForMember($memberProfile);
			$memberDatabaseRowResult = $memberDatabaseRow->getValueForKey('name');
			
			if(strcmp($memberDatabaseRowResult, "vorstand") == 0){
				$id = $this->getHierarchyIDByRessortname($memberDatabaseRowResult);
				return $id;
			} else {
			$memberDatabaseRow = $memberDataController->getPositionDatabaseRowForMember($memberProfile);
			echo "ROW : $memberDatabaseRow";
			$id = $this->getHierarchyIDByRessortname($memberDatabaseRow);	
			return $id;
			}
	}
}