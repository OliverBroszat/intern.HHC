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

	public function updateDataWithContactId($id) {
		$this->contactDatabaseRow->setValueForKey('id', $id);
		$this->updateContactIdFor($this->addressDatabaseRows);
		$this->updateContactIdFor($this->mailDatabaseRows);
		$this->updateContactIdFor($this->phoneDatabaseRows);
		$this->updateContactIdFor($this->studyDatabaseRows);
	}

	private function updateContactIdFor($rows) {
		// We assume that all contact related tables store the foreign key to the actual contact in a column named 'contact'
		$id = $this->contactDatabaseRow->getValueForKey('id');
		foreach ($rows as $row) {
			$row->setValueForKey('contact', $id);
		}
	}

}

?>