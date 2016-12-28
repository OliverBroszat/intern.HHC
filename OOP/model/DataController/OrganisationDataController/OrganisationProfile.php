<?php

class OrganisationProfile {
	public $organisationDatabaseRow;
	public $organisationMemberDatabaseRows;
	public $addressDatabaseRows;
	public $mailDatabaseRows;
	public $phoneDatabaseRows;

	public function __construct($organisationDatabaseRow, $organisationMemberDatabaseRows, $mailDatabaseRows, $phoneDatabaseRows, $addressDatabaseRows) {
		$this->organisationDatabaseRow = $organisationDatabaseRow;
		$this->organisationMemberDatabaseRows = $organisationMemberDatabaseRows;
		$this->addressDatabaseRows = $addressDatabaseRows;
		$this->mailDatabaseRows = $mailDatabaseRows;
		$this->phoneDatabaseRows = $phoneDatabaseRows;
	}

	public function updateDataWithOrganisationId($id) {
		$this->organisationDatabaseRow->setValueForKey('id', $id);
		$this->updateOrganisationIdFor($this->organisationMemberDatabaseRows);
		$this->updateOrganisationIdFor($this->addressDatabaseRows);
		$this->updateOrganisationIdFor($this->mailDatabaseRows);
		$this->updateOrganisationIdFor($this->phoneDatabaseRows);
	}

	private function updateOrganisationIdFor($rows) {
		// We assume that all Organisation related tables store the foreign key to the actual Organisation in a column named 'Organisation'
		$id = $this->organisationDatabaseRow->getValueForKey('id');
		foreach ($rows as $row) {
			$row->setValueForKey('Organisation', $id);
		}
	}
}