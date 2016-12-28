<?php
// ToDo: OrganisationMemberController
// replace the functions with OrganisationMemberDatabaseRows with the respective functions from the OrganisationMemberController

class OrganisationController {
	private $baseDataController;
    private $userSecurityPass;

	function __construct() {
		 $this->baseDataController = new BaseDataController();
	}

	/**
	 * create new object of class OrganisationProfile
	 * @return object OrganisationProfile
	 */
	public function createSingleOrganisationProfile($organisationDatabaseRow, $organisationMemberDatabaseRows, $mailDatabaseRows, $phoneDatabaseRows, $addressDatabaseRows) {
		return new OrganisationProfile(
			$organisationDatabaseRow, 
			$organisationMemberDatabaseRows, 
			$addressDatabaseRows,
			$mailDatabaseRows, 
			$phoneDatabaseRows
		);
	}


	/**
	 * create new OrganisationProfile in the database
	 * @param  object $organisationProfile 	objects of the class OrganisationProfile
	 */
	public function createSingleOrganisationByProfile($organisationProfile) {
		  $this->baseDataController->insertSingleRowWithAutoUpdateSingleAutoPrimaryInTable($organisationProfile->organisationDatabaseRow, 'Organisation');

        $newOrganisationId = $organisationProfile->organisationDatabaseRow->getValueForKey('id');
        $organisationProfile->updateDataWithOrganisationId($newOrganisationId);

        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($organisationProfile->organisationMemberDatabaseRows, 'OrganisationMembers');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($organisationProfile->addressDatabaseRows, 'Address');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($organisationProfile->mailDatabaseRows, 'Mail');
        $this->baseDataController->insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($organisationProfile->phoneDatabaseRows, 'Phone');
	}

	/**
	 * create multiple new OrganisationProfiles in the database
	 * @param  array $organisationProfiles 	array of objects of the class OrganisationProfile
	 */
	public function createMultipleOrganisationsByProfile($organisationProfiles) {
		foreach ($organisationProfiles as $organisationProfile) {
			$this->createSingleOrganisationByProfile($organisationProfile);
		}
	}

	public function getSingleOrganisationProfileById($id) {
		$foreignKey = 'Organisation';

		$organisationDatabaseRow = $this->baseDataController->getRowsFromTableByKey('Organisation', $id)[0];
        $organisationMemberDatabaseRows = $this->baseDataController->getRowsFromTableByKey('OrganisationMembers', $id, $foreignKey);
        $addressRows = $this->baseDataController->getRowsFromTableByKey('Address', $id, $foreignKey);
        $mailRows = $this->baseDataController->getRowsFromTableByKey('Mail', $id, $foreignKey);
        $phoneRows = $this->baseDataController->getRowsFromTableByKey('Phone', $id, $foreignKey);
        
        $organisationProfile = $this->createSingleOrganisationProfile($organisationDatabaseRow, $organisationMemberDatabaseRows, $addressDatabaseRows, $mailDatabaseRows, $phoneDatabaseRows);
		return $organisationProfile;
	}

	public function getMultipleOrganisationProfilesById($ids) {
		$organisationProfiles = array();
		foreach ($ids as $id) {
			$organisationProfile = getSingleOrganisationProfileById($id);
			array_push($organisationProfiles, $organisationProfile);
		}
		return $organisationProfiles;
	}


	public function deleteSingleOrganisationById($id) {
        return $this->baseDataController->deleteSingleRowFromTableByID('Organisation', $id);
    }

    public function deleteMultipleOrganisationsById($ids) {
        foreach ($ids as $id) {
            $this->deleteSingleOrganisationById($id);
        }
    }

    public function updateSingleOrganisationByProfile($organisationProfile) {
		// single rows
		$this->baseDataController->updateSingleRowInTable($organisationProfile->organisationDatabaseRow, 'Organisation');
		$this->baseDataController->updateSingleRowInTable($organisationProfile->organisationMemberDatabaseRows, 'OrganisationMembers');
		// old profile for comparison
    	$oldProfile = getSingleOrganisationProfileById($organisationProfile->organisationDatabaseRow->getValueForKey('id'));
		// multiple rows
    	$this->updateRowsByComparison($organisationProfile->addressDatabaseRows, $oldProfile->addressDatabaseRows, 'Address');
    	$this->updateRowsByComparison($organisationProfile->mailDatabaseRows, $oldProfile->mailDatabaseRows, 'Mail');
    	$this->updateRowsByComparison($organisationProfile->phoneDatabaseRows, $oldProfile->phoneDatabaseRows, 'Phone');
    }

    public function updateMultipleOrganisationsByProfile($organisationProfiles) {
    	foreach ($organisationProfiles as $organisationProfile) {
    		$this->updateSingleOrganisationByProfile($organisationProfile);
    	}
    }

    /**
     * update a set of DatabaseRows where old rows can be changed, new rows can be added or old rows can be deleted 
     * by comparing the new rows with the old rows
     * @param  array $newRows array of the DatabaseRows that should be saved to the database
     * @param  array $oldRows array of DatabaseRows that are in the database at the moment
     * @param  string $table 
     */
    private function updateRowsByComparison($newRows, $oldRows, $table) {
    	// get an array with the old Ids
    	// to check the Ids with the array_key_exists() function, the Ids are stored as a key
    	// to check if an old row has to be deleted, the value is true or false; by default, all rows get deleted
    	foreach ($oldRows as $oldRow) {
    		$oldRowsIds[$oldRow->getValueForKey('id')] = true;
    	}
    	foreach ($newRows as $newRow) {
    		// check if new rows are old rows to update or completly new and must be inserted
    		if (array_key_exists($newRow->getValueForKey('id'), $oldRowsIds)) {
    			// rows that are updated should not get deleted
    			$oldRowsIds[$newRow->getValueForKey('id')] = false;
    			// update row
    			$this->baseDataController->updateSingleRowInTable($newRow, $table);
    		}
    		else {
    			// insert new row
    			$this->baseDataController->insertSingleRowInTable($newRow, $table);
    		}
    	}
    	// delete rows
    	foreach ($oldRowsIds as $oldRowId => $delete) {
    		if ($delete) $this->baseDataController->deleteSingleRowFromTableByID($table, $oldRowId);
    	}
    }
}