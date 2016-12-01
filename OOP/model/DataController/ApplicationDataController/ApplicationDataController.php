<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class ApplicationDataController {
	private $ContactDataController;
	private $ApplicationDataModel;

	public function __construct() {
		$base = new BaseDataController();
		$this->ContactDataController = new ContactDataController(null, $base);
		$this->ApplicationDataModel = new ApplicationDataModel($base);		
	}

	public function createApplicationFromPostWithFiles($postvar) {
		$profile = $this->processPostToProfile($postvar);
		$applicationID = $this->createApplicationFromForm($profile);

		$attachmentIDs = $this->uploadFiles();
		$this->ApplicationDataModel->addMultipleAttachmentsToApplication($applicationID, $attachmentIDs, $postvar['File-filedescription']);

		// DEBUG
		$files = $this->ApplicationDataModel->getAttachmentsForApplication($applicationID);
		var_dump($files);
	}

	public function createApplicationFromForm($profile) {
		$this->ContactDataController->createSingleContactByProfile($profile);
		$contactID = $profile->contactDatabaseRow->getValueForKey('id');
		$this->ApplicationDataModel->createApplicationForContact($contactID);

		$newApplication = $this->ApplicationDataModel->getApplicationByContact($contactID);
		$newApplicationID = $newApplication->getValueForKey('id');

		return $newApplicationID;
	}

	public function processPostToProfile($postvar) {
		$post = post_to_array($postvar);
		$profileArray = array();

		$profileData = new DatabaseRow( (object) $post['Contact'][0] );
		$mailData = array();
		foreach ($post[Mail] as $mail) {
			$currentMailRow = new DatabaseRow( (object) $mail );
			array_push($mailData, $currentMailRow);
		}

		$phoneData = array();
		foreach ($post[Phone] as $phone) {
			$currentPhoneRow = new DatabaseRow( (object) $phone );
			array_push($phoneData, $currentPhoneRow);
		}

		$addressData = array();
		foreach ($post[Address] as $address) {
			$currentAddressRow = new DatabaseRow( (object) $address );
			array_push($addressData, $currentAddressRow);
		}

		$studyData = array();
		foreach ($post[Study] as $study) {
			$currentStudyRow = new DatabaseRow( (object) $study );
			array_push($studyData, $currentStudyRow);
		}
		$myProfile = new ContactProfile(
			$profileData,
			$addressData,
			$mailData,
			$phoneData,
			$studyData
		);
		return $myProfile;
	}

	private function uploadFiles() {
		if ( $_FILES ) { 
		    $files = $_FILES["File-apply_file"];  
		     $attachmentIDs = array();
		    foreach ($files['name'] as $key => $value) {            
	            if ($files['name'][$key]) { 
	                $file = array( 
	                    'name' => $files['name'][$key],
	                    'type' => $files['type'][$key], 
	                    'tmp_name' => $files['tmp_name'][$key], 
	                    'error' => $files['error'][$key],
	                    'size' => $files['size'][$key]
	                ); 
	                $_FILES = array ("File-apply_file" => $file);            
	                foreach ($_FILES as $file => $array) {              
	                   $newupload = $this->my_handle_attachment($file);
	                   array_push($attachmentIDs, $newupload);
	                }
					
	            } 
	        } 
	    }
	    echo "<hr>IDS:";
	    var_dump($attachmentIDs);
	    echo "<hr>";
	    return $attachmentIDs;
	}


	private function my_handle_attachment($file_handler) {
		// check to make sure its a successful upload
		if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		$attach_id = media_handle_upload( $file_handler, 0);
		if ( is_numeric( $attach_id ) ) {
			return $attach_id;
		}
	}

}

// DEBUG OUTPUT
echo '<b>Erhaltene Daten:<br></b>';
var_dump($_POST);
echo '<br><br><b>Erhaltene Dateien:<br></b>';
var_dump($_FILES);

echo "<hr>";


// Controlling
$ApplicationDataController = new ApplicationDataController();
$ApplicationDataController->createApplicationFromPostWithFiles($_POST);
