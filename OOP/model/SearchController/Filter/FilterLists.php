<?php 

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


// TODO: make static
class FilterLists {
	private $searchFilter;
    private $applicationFilter;

	public function __construct() {}


	public function getSearchFilter(){
		$this->searchFilter = array(
            "Ressort" => new FilterRessort(),
            "Position" => new FilterPosition(),
            "Status" => new FilterStatus(),
            "School" => new FilterSchool()
        );
        return $this->searchFilter;
	}

    //just an example
    public function getApplicationFilter(){
        $this->applicationFilter = array(
            "state" => new Filter('State of Application', 'application', 'state'),
            "School" => new FilterSchool()
        );
        return $this->applicationFilter;
    }
}