<?php
class MustacheData{
	private $userSecurityPass;
	
	public function __construct(){
		$this->userSecurityPass = new UserSecurityPass();
	}
	
	public function userCanEditMemberProfiles(){
		return $this->userSecurityPass->userHasRight("editMemberProfiles");
	}
}