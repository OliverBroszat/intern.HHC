<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.07.16
 * Time: 21:35
 */

// Load WP-Functions

$localhost = array('127.0.0.1', '::1');
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
}
require_once("$root/wp-load.php");

/*
 * Usage in a Template
 *
 * $page = PageFactory::createVisitorPage();
 * $userSecurityCard = new UserSecurityCard();
 * try {
 *     $userDataController = new UserDataController($userSecurityCard);
 *     $page = PageFactory::generateMemberPage('home');
 * }
 * catch (Exception e) {
 *     $page = new PageFactory::createErrorPage();
 * }
 * $page->render();
 *
 */

class UserSecurityPass {
	private $baseDataController;
	private $contactDataController;
	private $memberDataController;

    private $currentWPUser;

    public function __construct() {
        if ($this->userIsLoggedIn()) {
            $this->setupPassForMember();
        }
        else {
            $this->setupPassForVisitor();
        }
    }

    public function userIsLoggedIn() {
        return is_user_logged_in();
    }

    private function setupPassForMember() {
        $this->currentWPUser = wp_get_current_user();
        $this->createMemberDataController();
        
       $profile = $this->memberDataController->getCurrentMemberProfile();
    }

    private function setupPassForVisitor() {
        $this->currentWPUser = NULL;
        $this->createContactDataController();
    }

    // Getter / Setter

    public function getCurrentUserID() {
        return $this->currentUserID;
    }

    /**
     * Ermittelt ob der User eine bestimmte Rolle hat.
     * @param String $userRole
     * @return boolean der Rolle zugeordnet oder nicht
     */
    public function userHasRole($userRole) {
        //TODO machs richtig gez. Oli
        
    	return true;
    }
    
    /**
     * Ermittelt ob der User ein bestimmtes Recht hat.
     * @param String $userRight
     * @return boolean Recht vorhanden oder nicht
     */
    public function userHasRight($userRight) {
    	//TODO machs richtig gez. Oli
    	
    	return false;
    }
    
    /**
     * Ermittelt ob der User Mitglied einer bestimmten Gruppe ist.
     * @param unknown $userGroup
     * @return boolean Mittglied der Gruppe oder nicht
     */
    public function userHasGroup($userGroup) {
    	
    }
    
    private function createMemberDataController(){
    	$this->createContactDataController();
    	$this->memberDataController = new MemberDataController(null, $this->contactDataController);
    }
    
    private function createContactDataController(){  	
    	$this->baseDataController = new BaseDataController();
    	$this->contactDataController = new ContactDataController(null, $this->baseDataController);
    }
}

?>