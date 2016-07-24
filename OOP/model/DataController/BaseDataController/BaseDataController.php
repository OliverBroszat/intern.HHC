<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.07.16
 * Time: 23:07
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
 * Class BaseDataController
 *
 * Provides OO access to wordpress database and exception handling
 */
class BaseDataController {

    private $wpDatabaseConnection;

    /**
     * BaseDataController constructor.
     * @throws WordpressConnectionError
     */
    public function __construct() {
        $this->wpDatabaseConnection = $this->getWordpressDatabaseObject();
        if ($this->wpDatabaseConnection == null) {
            throw new WordpressConnectionError("Connection failed");
        }
    }

    public function tryToGetSingleRowByQuery($sqlQuery) {
        $requestedRow = $this->wpDatabaseConnection->get_row($sqlQuery);
        $this->onWordpressErrorThrowException();
        return new DatabaseRow($requestedRow);
    }

    public function tryToGetRowCollectionByQuery($sqlquery) {
        $requestedRowsInRawForm = $this->wpDatabaseConnection->get_results($sqlquery);
        $this->onWordpressErrorThrowException();

        $requestedRowsInCorrectForm = array();
        foreach ($requestedRowsInRawForm as $row) {
            array_push($requestedRowsInCorrectForm, new DatabaseRow($row));
        }
        return new DatabaseRowCollection($requestedRowsInCorrectForm);
    }

    protected function onWordpressErrorThrowException() {
        if ($this->wpDatabaseConnection->last_error != '') {
            $errorMessage = $this->wpDatabaseConnection->last_error;
            throw new WordpressExecutionError($errorMessage);
        }
    }

    private function getWordpressDatabaseObject() {
        global $wpdb;
        return $wpdb;
    }


}

