<?php

class OrganisationMemberProfile {
	public $organisationMemberDatabaseRow;
	public $contactProfile;

	public function __construct($organisationMemberDatabaseRow,
	$contactProfile) {
		$this->OrganisationMemberDatabaseRow = $organisationMemberDatabaseRow;
		$this->contactProfile = $contactProfile;
	}
}