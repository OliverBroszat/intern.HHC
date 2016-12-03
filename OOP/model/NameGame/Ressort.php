<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Ressort {
	public $name;
	public $description;

	public function __construct($ressortRow) {
	 $this->name = $ressortRow->getValueForKey('name');
	 $this->description = $ressortRow->getValueForKey('description');
	}
}