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