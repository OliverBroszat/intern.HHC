<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class FilterPosition extends Filter{
	private $filterNameChild = "HHC Position";
	private $tableChild = "Member";
	private $columnChild = "position";

	function __construct() {
		parent::__construct($this->filterNameChild, $this->tableChild, $this->columnChild, 2);
	}
}