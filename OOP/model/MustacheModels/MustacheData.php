<?php
class MustacheData{
	private $userSecurityPass;
	
	public function __construct(){
		$this->userSecurityPass = new UserSecurityPass();
	}
	
	public function userHasCanEdit(){
		return $this->userSecurityPass->userHasRight("edit");
	}
}