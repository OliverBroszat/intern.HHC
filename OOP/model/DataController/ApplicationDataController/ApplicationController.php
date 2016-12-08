<?php

/**
* Single ApplicationController
*/
class ApplicationController {
	private $applicationID;
	private $applicationDataModel;
	private $application;

	function __construct($applicationID) {
		$this->applicationID = $applicationID;
		$this->applicationDataModel = new ApplicationDataModel();
		$this->application = $this->applicationDataModel->getApplicationByID($applicationID);
	}

	public function changeState($state) {
		$applicationDataModel->setStateForApplication($this->applicationID, $state);
	}

	public function makeAppointment($date) {
		DateController::insertDate($date);
		
		$mail = MailController::newMail(array(
			'subject' => 'Termin', 
			'to' => $this->application->contactProfile->mailDatabaseRows[0]->getValueForKey('address'),
			'content' => '
				Hallo ' . 
					$this->application->contactProfile->contactDatabaseRow->getValueForKey('first_name') . ' ' . 
					$this->application->contactProfile->contactDatabaseRow->getValueForKey('last_name') .', 
				
				Du hast einen Termin für ein Vorstellungsgespräch bekommen. 
				Bitte komme am ' . $date->getStartDate() . ' um ' . $date->getStartTime() . ' zu ' . $date->getLocation() . '.
				
				Viele Grüße
				Dein HHC-Team'
		));

		MailController::sendMail($mail);
	}
}