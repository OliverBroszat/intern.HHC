<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class FilterSchool extends Filter{
	private $filterNameChild = "Universität";
	private $tableChild = "Study";
	private $columnChild = "school";

	function __construct() {
		parent::__construct($this->filterNameChild, $this->tableChild, $this->columnChild);
	}
}