<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class FilterRessort extends Filter{
	private $filterNameChild = "Ressort";
	private $tableChild = "Ressort";
	private $columnChild = "name";

	function __construct() {
		parent::__construct($this->filterNameChild, $this->tableChild, $this->columnChild, 2);
	}

	function getFilterQuery($array) {
		unset($array['class']);
		$array_string = array_to_string($array);

		// TODO: Prepare!!!!
		$sql = "
			SELECT Member.contact 
			FROM Ressort, Member
			WHERE Ressort.id = Member.ressort
			  AND Ressort.name IN ({$array_string});
		";
		return $sql;
	}
}