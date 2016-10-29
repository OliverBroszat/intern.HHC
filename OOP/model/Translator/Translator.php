<?php

class Translator {

	private $translations = array();

	
	public function __construct() {
		$this->translations = array(
			'contact' => array(
				'birth_date' => "change_date_format",
				'image' => function($id, $prefix) { return $this->getUrlforImage($id, $prefix); },
				'comment' => function($value) { return preg_replace('/\n/', '<br>', $value); }
			),
			'member' => array (
				'active' => array (
					'0' => 'Aktiv',
					'1' => 'Passiv'
				),			
				'position' => "uppercase",
				'name' => "uppercase", //ressort
				'joined' => "change_date_format",
				'left' => "change_date_format"
			),
			'phone' => array(
				'number' => function($value) { return $this->formatPhoneNumber($value); }
			),
			'study' => array(
				'status' => array(
					'active' => 'Aktiv',
					'done' => 'Abgeschlossen',
					'cancelled' => 'Abgebrochen'
				),
				'degree' => array(
					'b_sc' => 'Bachelor of Science',
					'm_sc' => 'Master of Science',
					'b_a' => 'Bachelor of Arts',
					'm_a' => 'Master of Arts',
					'exam' => 'Staatsexamen',
					'diplom' => 'Diplom'
				),
				'start' => "change_date_format",
				'end' => "change_date_format"
			)
		);
	}


	// private function uppercase($value) {
	// 	return uppercase($value);
	// }

	// private function changeDateFormat($value) {
	// 	return change_date_format($value);
	// }

	private function formatPhoneNumber($value) {
		if (substr($value, 0, 3) == '049') {
			// 049123456
			$value = '+49 '.substr($value, 3);
		}
		elseif (substr($value, 0, 4) == '0049') {
			// 0049123456
			$value = '+49 '.substr($value, 4);
		}
		elseif ($value[0] == '0') {
			// 0176123456
			$value = '+49 '.substr($value, 1);
		}
		elseif (substr($value, 0, 3) != '+49' && $value != '') {
			// 123456
			$value = '+49 '.$value;
		}
		return $value;
	}

	private function dateDiff($value1, $value2 = 'now', $format = '%y') {
		$date1 = date_create($value1);
		$date2 = date_create($value2);
		$dateDiff = date_diff($date1, $date2);
		return $dateDiff->format($format);
	}

	private function getUrlforImage($id, $prefix) {
		if (!empty($id)) {
			$imgsrc_thumb = wp_get_attachment_image_src($id, $size='thumbnail')[0];
			$imgsrc = wp_get_attachment_image_src($id, $size='')[0];
		}
		elseif ($prefix == 'Herr') {
			$imgsrc_thumb = get_template_directory_uri().'/resources/images/profile_placeholder_male.png';
			$imgsrc = '#';
		}
		else {
			$imgsrc_thumb = get_template_directory_uri().'/resources/images/profile_placeholder_female.png';
			$imgsrc = '#';
		}

		return array(
			'id' => $id,
			'source' => $imgsrc,
			'thumbnail' => $imgsrc_thumb
		);
		
	}


	public function translateContactDatabaseRow($contactDatabaseRow) {
		$translation = $this->translations['contact'];
		// Birth Dtae
		$contactDatabaseRow->setValueForKey('birth_date', $translation['birth_date']($contactDatabaseRow->getValueForKey('birth_date')));
		// Image
		$contactDatabaseRow->setValueForKey('image', $translation['image']($contactDatabaseRow->getValueForKey('image'), $contactDatabaseRow->getValueForKey('prefix')));
		// Comment
		$contactDatabaseRow->setValueForKey('comment', $translation['comment']($contactDatabaseRow->getValueForKey('comment')));
		return $contactDatabaseRow;
	}

	public function translateMemberDatabaseRow($memberDatabaseRow) {
		if (!empty($memberDatabaseRow)) {
			$translation = $this->translations['member'];
			$memberDatabaseRow->setValueForKey('active', $translation['active'][$memberDatabaseRow->getValueForKey('active')]);
			$memberDatabaseRow->setValueForKey('position', $translation['position']($memberDatabaseRow->getValueForKey('position')));
			$memberDatabaseRow->setValueForKey('name', $translation['name']($memberDatabaseRow->getValueForKey('name')));
			$memberDatabaseRow->setValueForKey('joined', $translation['joined']($memberDatabaseRow->getValueForKey('joined')));
			$memberDatabaseRow->setValueForKey('left', $translation['left']($memberDatabaseRow->getValueForKey('left')));
		}	
		return $memberDatabaseRow;
	}

	public function translateAddressDatabaseRows($addressDatabaseRows) {
		for ($i=0; $i < sizeof($addressDatabaseRows); $i++) {  }
		return $addressDatabaseRows;
	}

	public function translatePhoneDatabaseRows($phoneDatabaseRows) {
		$translation = $this->translations['phone'];
		for ($i=0; $i < sizeof($phoneDatabaseRows); $i++) { 
			$phoneDatabaseRows[$i]->setValueForKey('number', $translation['number']($phoneDatabaseRows[$i]->getValueForKey('number')));
		}
		return $phoneDatabaseRows;
	}

	public function translateMailDatabaseRows($mailDatabaseRows)	{
		for ($i=0; $i < sizeof($mailDatabaseRows); $i++) { 	}
		return $mailDatabaseRows;
	}

	public function translateStudyDatabaseRows($studyDatabaseRows) {
		$translation = $this->translations['study'];
		for ($i=0; $i < sizeof($studyDatabaseRows); $i++) { 
			// Status
			$studyDatabaseRows[$i]->setValueForKey('status', $translation['status'][$studyDatabaseRows[$i]->getValueForKey('status')]);
			// Degree
			if (array_key_exists($studyDatabaseRows[$i]->getValueForKey('degree'), $translation['degree'])) {
				$studyDatabaseRows[$i]->setValueForKey('degree', $translation['degree'][$studyDatabaseRows[$i]->getValueForKey('degree')]);
			}
		};
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



	public function translateSingleMemberProfile($memberProfile) {
		$memberProfile->ContactProfile = $this->translateContactProfile($memberProfile->contactProfile);
		$memberProfile->memberDatabaseRow = $this->translateMemberDatabaseRow($memberProfile->memberDatabaseRow);
		return $memberProfile;
	}

	public function translateMultipleMemberProfiles($memberProfiles) {
		$translatedProfiles = array();
		foreach ($memberProfiles as $memberProfile) {
			array_push($translatedProfiles, $this->translateSingleMemberProfile($memberProfile));
		}

		// print_r($translatedProfiles);
		return $translatedProfiles;
	}

	public function transformSingleMemberProfileToData($memberProfile, $encode=false) {
		$addresses = array();
		foreach ($memberProfile->contactProfile->addressDatabaseRows as $key => $value) {
			array_push($addresses, $value->toArray());
		}

		$mails = array();
		foreach ($memberProfile->contactProfile->mailDatabaseRows as $key => $value) {
			array_push($mails, $value->toArray());
		}

		$phones = array();
		foreach ($memberProfile->contactProfile->phoneDatabaseRows as $key => $value) {
			array_push($phones, $value->toArray());
		}

		$studies = array();
		foreach ($memberProfile->contactProfile->studyDatabaseRows as $key => $value) {
			array_push($studies, $value->toArray());
		}

		if(!empty($memberProfile->memberDatabaseRow)){
			$member  = $memberProfile->memberDatabaseRow->toArray();
		}
		else{ $member  = $memberProfile->memberDatabaseRow;	}

		// organize Data for single Member
		$data = array(
			'member' => $member,
			'contact' => $memberProfile->contactProfile->contactDatabaseRow->toArray(),
			'addresses' => $addresses,
			'mails' => $mails,
			'phones' => $phones,
			'studies' => $studies
		);

		return ($encode) ? $this->jsonEncode($data, $encode) : $data;
	}


	public function transformMultipleMemberProfilesToData($memberProfiles) {
		$data = array();
		foreach ($memberProfiles as $number => $memberProfile) {
			$member = $this->transformSingleMemberProfileToData($memberProfile);
			$member['number'] = $number + 1;
			array_push($data, $member);
		}
		return $data;
	}

	/**
	 * jsconEncode
	 * 
	 * encodes array to json
	 *
	 * @param data Dataset created by transformSingleMemberProfileToData from memberProfile
	 * @return data json encoed
	 */
	private function jsonEncode($data, $encode) {
		foreach ($data as $key => $values) {
			if (in_array($key, $encode)) {
				$data[$key] = json_encode($values);
			}
		}
		return $data;
	}
}