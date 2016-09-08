<?php

class MemberProfile {

	public $memberDatabaseRow;
	public $contactProfile;

	public function __construct($memberDatabaseRow, $contactProfile) {
		$this->memberDatabaseRow = $memberDatabaseRow;
		$this->contactProfile = $contactProfile;
	}

}

?>