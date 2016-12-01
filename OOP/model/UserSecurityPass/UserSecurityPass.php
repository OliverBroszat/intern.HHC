<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.07.16
 * Time: 21:35
 */

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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
        return $this->currentUserID;
    }

    public function userHasRole($userRole) {
        
    }
}

$securityPass = new UserSecurityPass();
if ($securityPass->userIsLoggedIn()) {
    echo 'Hallo, Mitglied!';
}
else {
    echo 'Hallo, Fremder!';
}

?>