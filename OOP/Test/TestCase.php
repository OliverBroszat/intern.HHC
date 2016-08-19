<?php

/*

IMPORTANT!!!
NOT READY TO USE!!!

TestCases (can be found in Test/Modules/...) doesnt inherit frrom this class since the infrastructure
is not ready for that. Give me some more days ;-)

TODO:
- add a test dispatcher to run tests from a central point
- unitTest Template should have a function loadClass to require the test cases, create objects and get the 
	test information server-side withour GET

Finish that later...

*/

class TestCase {

	protected $information = "This is a generic information strig and should be updated!";
	
	public function start() {
		$command = $_GET['command'];

		if ($command == 'info') {
			echo $this->getJSONInformation();
		}
		elseif ($command == 'run') {
			echo getResultsFromTest();
		}
		else {
			echo $this->getUnknownCommandError();
		}
	}

	protected final function getJSONInformation() {
		$informationData = array(
			'test-name' => $this->getTestName(),
			'test-info' => $this->getInformation()
		);
		return json_encode($informationData);
	}

	protected final function getTestName() {
		return get_class($this);
	}

	public function getInformation() {
		return $this->information;
	}

	protected final function getUnknownCommandError() {
		$data = array(
			'success' => false,
			'message' => 'Just a random error! :D'
		);
		return json_encode($data);
	}

	protected final function getResultsFromTest() {
		// Bereite Array für Test vor
	}

	protected function runTest() {
		// TODO: Overwrite this method with your test code!
	}
}

?>