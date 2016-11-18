<?php

/*
 * Because we're outside the wordpress template directory, no wordpress functionality will be
 * pre-includes for us :(
 * In this block we include all necessary functions and an autoloader by loading the 'wp-load.php'.
 * NOTE: on local host this file has a different path!
 */
if (!function_exists('serverIsRunningOnLocalHost')) {
    function serverIsRunningOnLocalHost() {
        $localHostAddresses = array('127.0.0.1', '::1');
        $currentServerIPAddress = $_SERVER['REMOTE_ADDR'];
        if(in_array($currentServerIPAddress, $localHostAddresses)){
            return true;
        }
        return false;
    }
}
if (!function_exists('loadWordpressFunctions')) {
    function loadWordpressFunctions() {
        $serverRootPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        if (serverIsRunningOnLocalHost()) {
            $serverRootPath = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
        }
        require_once("$serverRootPath/wp-load.php");
    }
}
loadWordpressFunctions();


/**
 * class CSV
 * 
 * Transforms data into the .csv file format (especially memberProfiles)
 */
class CSV {
	private $csv;
	private $maxNumberOfDatabaseRows;
	private $seperator;
	private $base;
	
	/**
	 * initializes the structure of the local variables
	 */
	public function __construct() {
		$this->csv = array('header' => array(), 'body' => array());
		$this->maxNumberOfDatabaseRows = array('contact' => 1, 'member' => 1, 'phone' => 0, 'mail' => 0, 'address' => 0, 'study' => 0);
		$this->base = new BaseDataController();	
	}
	
	/**
	 * shortcut function to combine structering the csv data in an array for further use and formatting it to a string
	 * @param  array $memberProfiles array of objects of the class MemberProfile
	 * @param  string $seperator      seperator for the string output of the csv data; default: ';'
	 * @return string                 string with formatted data for a csv file
	 */
	public function generateCsvForMemberProfilesAsString($memberProfiles, $seperator = ';'){
		$this->seperator = $seperator;
		$this->generateCsvForMemberProfiles($memberProfiles);
		return $this->printCSV();
	}

	/**
	 * transform multiple MemberProfiles into the csv structure
	 * @param  array $memberProfiles array of objects of the class MemberProfile
	 */
	public function generateCsvForMemberProfiles($memberProfiles) {
		// get max number of databseRows per table (e.g. max number of mails, a member has)
		$this->getMaxNumberOfDatabaseRowsForMemberProfiles($memberProfiles);
		// generate header of csv file
		$this->generateCsvHeader(array('contact', 'phone', 'mail', 'address', 'study', 'member'));
		// generate body of csv file
		foreach($memberProfiles as $memberProfile) {
			$this->generateCsvForMemberProfile($memberProfile);
		}
		// return array with the data for a csv file, sperated in header and body
		return $this->csv;
	}

	/**
	 * get the headers for the csv file
	 * @param  array $tableNames array with the names of the tables where the data comes from; needs to be in the same order as the data is stored in the body
	 * @return string	e.g. table1-column1-1, table1-column2-1, table1-column1-2, table1-column2-2, table2-column1-1 ...
	 */
	private function generateCsvHeader($tableNames){
		foreach ($tableNames as $tableName) {
			$columns = $this->base->getColumnNamesForTable($tableName);		
			
			for ($i=1; $i <= $this->maxNumberOfDatabaseRows[$tableName]; $i++) { 
				$this->maxNumberOfDatabaseRows[$tableName] > 1 ? $index="-$i" : $index ='' ;
				foreach ($columns as $column) {
					array_push($this->csv['header'], "{$tableName}-{$column}{$index}");
				}
			}
		}
	}

	/**
	 * get the biggest number of databebaseRows in a list of databseRows from all memberProfiles
	 * @param  array $memberProfiles array of objects of the class MemberProfile
	 */
	private function getMaxNumberOfDatabaseRowsForMemberProfiles($memberProfiles) {
		foreach ($memberProfiles as $memberProfile) {
			$this->getMaxNumberOfDatabaseRowsForMemberProfile($memberProfile);
		}
	}

	/**
	 * get the biggest number of databebaseRows in a list of databseRows from a single memberProfile
	 * @param  object $memberProfiles object of the class MemberProfile
	 */
	private function getMaxNumberOfDatabaseRowsForMemberProfile($memberProfile){
		$this->getMaxNumberOfDatabaseRows($memberProfile->contactProfile->phoneDatabaseRows, 'phone');
		$this->getMaxNumberOfDatabaseRows($memberProfile->contactProfile->mailDatabaseRows, 'mail');
		$this->getMaxNumberOfDatabaseRows($memberProfile->contactProfile->addressDatabaseRows, 'address');
		$this->getMaxNumberOfDatabaseRows($memberProfile->contactProfile->studyDatabaseRows, 'study');
	}
	
	/**
	 * get the biggest number of databebaseRows in a list of databseRows from a single memberProfiles
	 * @param  array $databaseRows array of objects of the class MemberProfiles
	 * @param  string $tableName    name of the table where the databaseRow comes from
	 */
	private function getMaxNumberOfDatabaseRows($databaseRows, $tableName) {
		$numberOfDatabaseRows = sizeof($databaseRows);
		if($numberOfDatabaseRows > $this->maxNumberOfDatabaseRows[$tableName]) { 
			$this->maxNumberOfDatabaseRows[$tableName] = $numberOfDatabaseRows; 
		}		
	}

	/**
	 * transform a single MemberProfile into the csv structure
	 * @param  object $memberProfiles object of the class MemberProfile
	 */
	public function generateCsVForMemberProfile($memberProfile) {
		// push an empty array to csv['body'] as a container for all values of one member
		array_push($this->csv['body'], array());
		// insert all values of one member to the new empty array
		$this->generateCsvForContactProfile($memberProfile->contactProfile);
		$this->generateCsvForDatabaseRow($memberProfile->memberDatabaseRow, 'member');
	}

	/**
	 * transform multiple ContactProfiles into the csv structure
	 * @param  array $memberProfiles array of objects of the class ContactProfile
	 */
	public function generateCsvForContactProfiles($contactProfiles) {
		foreach($contactProfiles as $contactProfile) {
			$this->generateCsvForContactProfile($contactProfile);
		}
		return $this->csv;
	}

	/**
	 * transform a single ContactProfile into the csv structure
	 * @param  object $contactProfile object of the class ContactProfile
	 */
	public function generateCsvForContactProfile($contactProfile) {
		$this->generateCsvForDatabaseRow($contactProfile->contactDatabaseRow, 'contact');
		$this->generateCsvForDatabaseRows($contactProfile->phoneDatabaseRows, 'phone');
		$this->generateCsvForDatabaseRows($contactProfile->mailDatabaseRows, 'mail');
		$this->generateCsvForDatabaseRows($contactProfile->addressDatabaseRows, 'address');
		$this->generateCsvForDatabaseRows($contactProfile->studyDatabaseRows, 'study');
	}

	/**
	 * transform multiple DatabaseRows into the csv structure
	 * @param  array $databaseRows array of objects of the class DatabaseRow
	 * @param  string $tableName	name of the table where the databaseRow comes from
	 */
	public function generateCsvForDatabaseRows($databaseRows, $tableName = '') {
		for ($i=0; $i < $this->maxNumberOfDatabaseRows[$tableName]; $i++) { 
			if(!empty($databaseRows)) {
				$keys = array_keys($databaseRows);
				$this->generateCsvForDatabaseRow($databaseRows[$keys[$i]], $tableName);	
			}
			else {
				$this->generateCsvForEmptyDatabseRows($tableName);
			}
		}
		
	}

	/**
	 * transform a single DatabaseRow into the csv structure
	 * @param  object $databaseRow object of the class DatabaseRow
	 * @param  string $tableName	name of the table where the databaseRow comes from
	 */
	public function generateCsvForDatabaseRow($databaseRow, $tableName='') {
		if(!empty($databaseRow)) {
			foreach($databaseRow->toArray() as $key => $value) {
				// push value to the last item of csv['body']
				array_push($this->csv['body'][sizeof($this->csv['body'])-1], $value);
			}
		}
		else {
			$this->generateCsvForEmptyDatabseRows($tableName);
		}
	}

	/**
	 * insert empty data to the csv structure to compensate for empty DatabaseRows
	 * @param  string $tableName	name of the table where the databaseRow comes from
	 */
	private function generateCsvForEmptyDatabseRows($tableName) {
		$numberOfColumns = $this->base->getNumberOfColumnsForTable($tableName);
		for($i=0; $i < $numberOfColumns; $i++) {
			// push value to the last item of csv['body']
			array_push($this->csv['body'][sizeof($this->csv['body'])-1], '');
		}
	}


	/**
	 * print the whole csv array to a string in the format of a .csv file
	 * @param  string $seperator      seperator for the string output of the csv data; default: ';'
	 * @return string string in the format of a csv file
	 */
	public function printCSV($seperator = ';'){
		$this->seperator = $seperator;
		$string = $this->printCsvRow($this->csv['header']);
		foreach($this->csv['body'] as $row) {
			$string .= $this->printCsvRow($row);
		}
		return $string;
	}

	/**
	 * print a single row of value in the format of a .csv file
	 * @param  array $row array of values to print
	 * @return string      string in the format of a csv file
	 */
	private function printCsvRow($row) {
		$string = '';
		foreach ($row as $value) {
			// remove line-breaks and whitespace at the start end end of a value and unify the values a little bit with the uppercase function (located in functions.php) and add a seperator after each value
			$string .= uppercase(trim(preg_replace('/\s+/', ' ', $value))).$this->seperator;			
		}
		$string .= "\n";
		return $string;
	}
}