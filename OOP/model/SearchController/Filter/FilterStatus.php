<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class FilterStatus extends Filter{
	private $filterNameChild = "HHC Status";
	private $tableChild = "Member";
	private $columnChild = "active";

	function __construct() {
		parent::__construct($this->filterNameChild, $this->tableChild, $this->columnChild, 2);
	}
}