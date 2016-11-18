<?php

class ApplicationProfile {

	public $applicationDatabaseRow;
	public $contactProfile;
	public $attachmentDatabaseRows;

	public function __construct($applicationDatabaseRow, $contactProfile, $attachmentDatabaseRows) {
		$this->applicationDatabaseRow = $applicationDatabaseRow;
		$this->contactProfile = $contactProfile;
		$this->attachmentDatabaseRows = $attachmentDatabaseRows;
	}
}