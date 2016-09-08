<?php

class Application {
	public $contactProfile;
	public $applicationattachmentDatabaseRows;
	public $applicationDatabaseRow;

	public function __construct($contactProfile, $applicationattachmentDatabaseRows, $applicationDatabaseRow) {
		$this->contactProfile = $contactProfile;
		$this->applicationattachmentDatabaseRows = $applicationattachmentDatabaseRows;
		$this->applicationDatabaseRow = $applicationDatabaseRow;
	}

}

?>