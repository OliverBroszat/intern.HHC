<?php

class Translator {

	private $tempString = '';

	// private $transaltions = array(
	// 	'contact' => array(
	// 		'birth_date' => $this->change_date_format()
	// 	),
	// 	'member' => array (
	// 		'ressort' => $this->uppercase(),
	// 		'status' => array (
	// 			'0' => 'Aktiv',
	// 			'1' => 'Passiv'
	// 		)
	// 	)
	// );

	
	public function __construct() {

	}


	private function uppercase() {
		return uppercase($this->tempString);
	}

	private function change_date_format() {
		return change_date_format($this->tempString);
	}


	public function translateContactDatabaseRow($contactDatabaseRow) {
		// $this->tempString = $contactDatabaseRow->birth_date;
		// $contactDatabaseRow->birth_date = $this->$translations['contact']['birth_date'];
		$contactDatabaseRow->setValueForKey('birth_date', change_date_format($contactDatabaseRow->getValueForKey('birth_date')));
		return $contactDatabaseRow;
	}

	public function translateMemberDatabaseRow($memberDatabaseRow) {
		$memberDatabaseRow->setValueForKey('position', uppercase($memberDatabaseRow->getValueForKey('position')));
		$memberDatabaseRow->setValueForKey('name', uppercase($memberDatabaseRow->getValueForKey('name')));
		$memberDatabaseRow->setValueForKey('joined', change_date_format($memberDatabaseRow->getValueForKey('joined')));
		$memberDatabaseRow->setValueForKey('left', change_date_format($memberDatabaseRow->getValueForKey('left')));
		return $memberDatabaseRow;
	}

	public function translateAddressDatabaseRows($addressDatabaseRows) {
		foreach ($addressDatabaseRows as $addressDatabaseRow) {

		}
		return $addressDatabaseRows;
	}

	public function translatePhoneDatabaseRows($phoneDatabaseRows) {
		foreach ($phoneDatabaseRows as $phoneDatabaseRow) {

		}
		return $phoneDatabaseRows;
	}

	public function translateMailDatabaseRows($mailDatabaseRows)	{
		foreach ($mailDatabaseRows as $mailDatabaseRow) {

		}
		return $mailDatabaseRows;
	}

	public function translateStudyDatabaseRows($studyDatabaseRows) {
		for ($i=0; $i < sizeof($studyDatabaseRows); $i++) { 

		}
		return $studyDatabaseRows;
	}



	public function translateContactProfile($contactProfile) {
		$contactProfile->contactDatabaseRow = $this->translateContactDatabaseRow($contactProfile->contactDatabaseRow);
		$contactProfile->addressDatabaseRows = $this->translateAddressDatabaseRows($contactProfile->addressDatabaseRows);
		$contactProfile->phoneDatabaseRows = $this->translatePhoneDatabaseRows($contactProfile->phoneDatabaseRows);
		$contactProfile->mailDatabaseRows = $this->translateMailDatabaseRows($contactProfile->mailDatabaseRows);
		$contactProfile->studyDatabaseRows = $this->translateStudyDatabaseRows($contactProfile->studyDatabaseRows);
		return $contactProfile;
	}



	public function translateMemberProfile($memberProfile) {
		$memberProfile->ContactProfile = $this->translateContactProfile($memberProfile->contactProfile);
		$memberProfile->memberDatabaseRow = $this->translateMemberDatabaseRow($memberProfile->memberDatabaseRow);
		return $memberProfile;
	}
}