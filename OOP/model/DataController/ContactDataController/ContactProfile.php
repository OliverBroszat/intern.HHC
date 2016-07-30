<?php

class ContactProfile {

	public $contactDatabaseRow;
	public $addressDatabaseRows;
	public $mailDatabaseRows;
	public $phoneDatabaseRows;
	public $studyDatabaseRows;

	public function __construct($contactDatabaseRow, $addressDatabaseRows, $mailDatabaseRows, $phoneDatabaseRows, $studyDatabaseRows) {
		$this->contactDatabaseRow = $contactDatabaseRow;
		$this->addressDatabaseRows = $addressDatabaseRows;
		$this->mailDatabaseRows = $mailDatabaseRows;
		$this->phoneDatabaseRows = $phoneDatabaseRows;
		$this->studyDatabaseRows = $studyDatabaseRows;
	}

}

?>