<?php

// wordpress autoloader 
require_once(explode('wp-content',__DIR__)[0].'wp-load.php'); 

echo "<button type='button' class='popup-close' style='margin: 1rem auto; display: block;'>Schließen</button>";
echo "<hr>";
echo "<h2>DEBUG-Output</h2>";


/**
* ProcessDataFromEditForm
* clean up and sort $_POST date
* validate data
* call crud functions: update member, delete member, new member
*/
class ProcessDataFromEditForm
{
	private $baseDataController;
	private $contactDataController;
	private $memberDataController;

	private $modus;
	private $imageModus;
	private $id;


	function __construct($post, $files) {
		$this->baseDataController = new BaseDataController();
		$this->contactDataController = new ContactDataController(null, $this->baseDataController);
		$this->memberDataController = new MemberDataController(null, $this->contactDataController);

		$this->modus = $post['crud-mode'];
		$this->id = $post['crud-id'];

		$this->processData($post, $files);
	}

	public function processData($post, $files) {
		if ($this->modus == 'edit') {
			// clean Post-Data
			$cleanData = $this->postToArray($this->cleanupPost($post));
			// transform data array to DatabaseRows
			$dataObject = $this->transformDataToDatabaseRows($cleanData);
			// create MemberProfile from Post-Data
			$newMemberProfile = $this->createNewMemberProfile($dataObject);
			// validate Member-Profile
			$this->validateRequiredFields($newMemberProfile);
			// process Image
			$newMemberProfileWithImage = $this->processImageData($newMemberProfile, $cleanData['crud']['delete_image'], $files);
			echo "<h1>newMemberProfileWithImage</h1>";
			arr_to_list($newMemberProfileWithImage);

			if (empty($this->id)) {
				// NEW Member
				echo "INSERT ";
				$this->memberDataController->createSingleMemberByProfile($newMemberProfileWithImage);
			}
			else {
				// UPDATE Member
				echo "UPDATE ";	
				$this->memberDataController->updateSingleMemberProfile($newMemberProfileWithImage);
			}
		}
		elseif($this->modus == 'delete') {
			// DELETE Member
			echo "DELETE ";
			$this->memberDataController->deleteSingleMemberByID($this->id);
		}
	}


	/**
	 * Lösche alle Einträge mit 'other' und indiziere das Array neu
	 */
	private function cleanupPost($post) {
		// Suche alle Einträge mit 'other' mit search_in_2d_array() aus function.php 
		$result = search_in_2d_array($post, 'other');

		$cleanPost = $post;
		foreach ($result as $res) {
			$key = $res['key'];
			$index = $res['index'];
			// löschen
			unset($cleanPost[$key][$index]);
			// neu indizieren
			$cleanPost[$key] = array_values($cleanPost[$key]);
		}
		return $cleanPost;
	}

	/** 
	 * wandele die Post-Struktur in ein sortiertes Array um mit post_to_array() aus function.php
	 */
	private function postToArray($post) {
		return post_to_array($post);
	}

	private function transformDataToDatabaseRows($data) {
		$dataObject = array();
		foreach ($data as $table_name => $tables) {
			print_r("aktuelle Tabelle: $table_name<br>");
			foreach ($tables as $index => $values) {
				print_r("Aktueller Index: $index<br>");
				var_dump($values);
				echo '<br>';
				$dataObject[$table_name][$index] = new DatabaseRow((object) $values);
			}
		}
		$finalDataObject = $this->checkForMissingDatabaseRows($dataObject);
		return $finalDataObject;
	}

	private function checkForMissingDatabaseRows($dataObject) {
		// Workarround for missing datastructures if empty
		$tablesNames = array('Address', 'Mail', 'Phone', 'Study');
		foreach ($tablesNames as $tableName) {
			if (!isset($dataObject[$tableName])) {
				echo "<br>$tableName fehlt, also setze leeres Array<br>";
				$dataObject[$tableName] = array();
			}
		}
		return $dataObject;
	}

	private function createNewMemberProfile($dataObject) {
		$memberProfile = new MemberProfile(
			$dataObject['Member'][0],
			$this->createNewContactProfile($dataObject)
		);
		return $memberProfile;
	}

	private function createNewContactProfile($dataObject) {
		$contactProfile = new ContactProfile(
			$dataObject['Contact'][0],
			$dataObject['Address'],
			$dataObject['Mail'],
			$dataObject['Phone'],
			$dataObject['Study']
		);
		$contactProfile->updateDataWithContactId($this->id);
		return $contactProfile;
	}

	private function validateRequiredFields($memberProfile) {
		$requiredFieldsForContact = array('prefix', 'first_name', 'last_name', 'birth_date');
		$requiredFieldsForAddress = array('description', 'street', 'number', 'postal', 'city');
		$requiredFieldsForMail = array('description', 'address');
		$requiredFieldsForPhone = array('description', 'number');
		$requiredFieldsForStudy = array('status', 'school', 'course', 'degree', 'start');
		$requiredFieldsForMember = array('ressort', 'active', 'position', 'joined');

		$newProfile = $memberProfile->contactProfile;
		$newMember = $memberProfile;

		$contactRow = $newProfile->contactDatabaseRow;
		if (!$this->allFieldsSetInRow($requiredFieldsForContact, $contactRow)) {
			die('Invalid Contact Data!');
		}
		$memberRow = $newMember->memberDatabaseRow;
		var_dump(empty("0"));
		if (!$this->allFieldsSetInRow($requiredFieldsForMember, $memberRow)) {
			die('Invalid Member Data!');
		}

		foreach ($newProfile->addressDatabaseRows as $id => $addrRow) {
			if ( (!$this->allFieldsSetInRow($requiredFieldsForAddress, $addrRow)) && ($this->atLeastOneFieldSetInRow($requiredFieldsForAddress, $addrRow)) ) {
				die('Invalid Address Data!');
			}
			if ($this->allFieldsEmptyInRow($requiredFieldsForAddress, $addrRow)) {
				echo '<br>ENTFERNE: ';
				var_dump($addrRow);
				echo '<br>';
				unset($newProfile->addressDatabaseRows[$id]);
			}
		}
		foreach ($newProfile->mailDatabaseRows as $id => $row) {
			if ( (!$this->allFieldsSetInRow($requiredFieldsForMail, $row)) && ($this->atLeastOneFieldSetInRow($requiredFieldsForMail, $row)) ) {
				die('Invalid Mail Data!');
			}
			if ($this->allFieldsEmptyInRow($requiredFieldsForMail, $row)) {
				echo '<br>ENTFERNE: ';
				var_dump($row);
				echo '<br>';
				unset($newProfile->mailDatabaseRows[$id]);
			}
		}
		foreach ($newProfile->phoneDatabaseRows as $id => $row) {
			if ( (!$this->allFieldsSetInRow($requiredFieldsForPhone, $row)) && ($this->atLeastOneFieldSetInRow($requiredFieldsForPhone, $row)) ) {
				die('Invalid Phone Data!');
			}
			if ($this->allFieldsEmptyInRow($requiredFieldsForPhone, $row)) {
				echo '<br>ENTFERNE: ';
				var_dump($row);
				echo '<br>';
				unset($newProfile->phoneDatabaseRows[$id]);
			}
		}
		foreach ($newProfile->studyDatabaseRows as $id => $row) {
			if ( (!$this->allFieldsSetInRow($requiredFieldsForStudy, $row)) && ($this->atLeastOneFieldSetInRow($requiredFieldsForStudy, $row)) ) {
				die('Invalid Study Data!');
			}
			if ($this->allFieldsEmptyInRow($requiredFieldsForStudy, $row)) {
				echo '<br>ENTFERNE: ';
				var_dump($row);
				echo '<br>';
				unset($newProfile->studyDatabaseRows[$id]);
			}
		}
	}

	private function isNullOrEmptyString($data) {
		return is_null($data) || ($data == '');
	}

	private function allFieldsSetInRow($fields, $row) {
		foreach ($fields as $field) {
			if ($this->isNullOrEmptyString($row->getOptionalValueForKey($field))) {
				print_r("$field wurde nicht gefunden");
				return false;
			}
		}
		return true;
	}

	private function atLeastOneFieldSetInRow($fields, $row) {
		foreach ($fields as $field) {
			if (!$this->isNullOrEmptyString($row->getOptionalValueForKey($field))) {
				return true;
			}
		}
		return false;
	}

	private function allFieldsEmptyInRow($fields, $row) {
		return !$this->atLeastOneFieldSetInRow($fields, $row);
	}

	private function processImageData($memberProfile, $deleteImage, $files) {
		$oldAttachmentId = $this->getOldAttachementId();

		if (!$dleteImage) {
			if (!empty($files['upload_image']['name'])) {
				// upload new Image
				echo "NEW IMAGE";
				$this->insertNewImage($memberProfile);
			}
			elseif(!empty($oldAttachmentId)) {
				// use old image
				echo "OLD IMAGE";
				$memberProfile->contactProfile->contactDatabaseRow->setValueForKey('image', $oldAttachmentId);
			}
			else {
				// no image/delete old image
				echo "NO IMAGE";
			}
		}
		else {
			echo 'DELETE IMAGE';
			$memberProfile->contactProfile->contactDatabaseRow->setValueForKey('image', null);
		}

		return $memberProfile;
	}

	private function getOldAttachementId() {
		// Get Image ID
		global $wpdb;
		$query = "SELECT image FROM Contact WHERE id=%d";
		$query_escaped = $wpdb->prepare($query, $this->id);
		try {
			$attachment_id = $this->baseDataController->selectSingleRowByQuery($query_escaped)->getValueForKey('image');
			echo 'THE ID: ';
			var_dump($attachment_id);
			echo '<br><br>';
		}
		catch (LengthException $e) {
		}
	}

	private function insertNewImage($memberProfile) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
		$newAttachmentId = media_handle_upload('upload_image',0);
				
		if (!is_wp_error($newAttachmentId)) {
			// The image was uploaded successfully!
			$memberProfile->contactProfile->contactDatabaseRow->setValueForKey('image', $newAttachmentId);
		} 
		else {		
			echo "<br>ERROR (Image Upload)<br>";
		}
	}

}


return new ProcessDataFromEditForm($_POST, $_FILES);

echo "<br><b>Contact-ID:</b> $id";

//Debug Output continue
echo "<br><b>Errors:</b> ";
if (!empty($wpdb->last_error)) {
	echo "<pre>".$wpdb->last_error."</pre>";
}
else {
	echo "NO ERRORS";
}
echo "<hr>";

echo "<h3>DataObject</h3>";
arr_to_list($dataObject);
echo "<hr>";

echo "<h3>Files</h3>";
arr_to_list($_FILES);
echo "<hr>";

echo "<h3>Queries</h3>";
echo "<pre>";
print_r($wpdb->queries);
echo "</pre>";

echo "<hr>";