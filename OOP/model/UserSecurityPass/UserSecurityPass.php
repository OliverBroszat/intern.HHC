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
    }

    private function setupPassForVisitor() {
        $this->currentWPUser = NULL;
    }

    // Getter / Setter

    public function getCurrentUserID() {
        $currentUser = wp_get_current_user();
        return $currentUser->ID;
    }

    public function userHasRole($userRole) {
        
    }
}
?>