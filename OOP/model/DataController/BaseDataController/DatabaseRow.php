<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.07.16
 * Time: 23:36
 */


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

if (!function_exists('getServerRootPath')) {
    function getServerRootPath() {
        $serverRootPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        if (serverIsRunningOnLocalHost()) {
            $serverRootPath = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
        }
        return $serverRootPath;
    }
}

if (!function_exists('loadWordpressFunctions')) {
    function loadWordpressFunctions() {
        $serverRootPath = getServerRootPath();
        require_once("$serverRootPath/wp-load.php");
    }
}

loadWordpressFunctions();

/**
 * Class DatabaseRow
 *
 * Wraps the wordpress SQL query results
 $result = wpdb->gt_result...
 */
class DatabaseRow
{

    public static function filterValuesFromRowsForSingleKey($key, $rows) {
        $valuesForKey = array();
        foreach ($rows as $row) {
            array_push($valuesForKey, $row->getValueForKey($key));
        }
        return $valuesForKey;
    }

    public static function filterValuesFromRowsForMultipleKeys($keys, $rows) {
        $filtered = array();
        foreach ($keys as $key) {
            $filtered['$key'] = $this->filterValuesFromRowsForKey($key, $rows);
        }
        return $filtered;
    }

    // TODO: add static method FromArray

    private $sqlQueryResult;

    public function __construct($sqlQueryResult)
    {
        $this->sqlQueryResult = $sqlQueryResult;
    }

    public function getValueForKey($key)
    {
        $this->ifKeyNotExistingThrowException($key);
        $valueForKey = $this->sqlQueryResult->$key;
        return $valueForKey;
    }

    // Will return the key or null
    public function getOptionalValueForKey($key) {
        return $this->sqlQueryResult->$key;
    }
    
    // NOTE: Prüft nicht, ob der Wert 'key' existiert!
    public function setValueForKey($key, $value) {
        $this->sqlQueryResult->$key = $value;
    }

    // Prüft ob der Wert auch schon existiert
    public function setValueForExistingKey($key, $value) {
        $this->ifKeyNotExistingThrowException($key);
        $this->setValueForKey($key, $value);
    }

    private function ifKeyNotExistingThrowException($key) {
        $attributes = $this->getColumnNames();
        if (!in_array($key, $attributes)) {
            $errorMessage = "The key '$key' does not exist. Maybe use setValueForKey()?";
            throw new InvalidArgumentException($errorMessage);
        }
    }

    public function getColumnNames() {
        $publicAttributes = get_object_vars($this->sqlQueryResult);
        $columnNames = array();
        foreach ($publicAttributes as $name => $content) {
            array_push($columnNames, $name);
        }
        return $columnNames;
    }

    public function toArray() {
        $dataArray = array();
        $columns = $this->getColumnNames();
        foreach ($columns as $columnName) {
            $dataArray[$columnName] = $this->getValueForKey($columnName);
        }
        return $dataArray;
    }

    public function deleteSingleColumnWithName($name) {
        unset($this->sqlQueryResult->$name);
    }

    public function deleteMultipleColumnsWithName($names) {
        foreach ($names as $name) {
            $this->deleteSingleColumnWithName($name);
        }
    }
}
