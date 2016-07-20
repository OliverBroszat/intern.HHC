<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.07.16
 * Time: 23:07
 */

// Load WP-Functions

$localhost = array(
    '127.0.0.1',
    '::1'
);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
}

require_once("$root/wp-load.php");


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

    public function tryToGetSingleRowByQuery($SQLquery) {
        $requestedRow = $this->wpDatabaseConnection->get_row($SQLquery);
        $this->onWordpressErrorThrowException();
        return new DatabaseRow($requestedRow);
    }

    private function onWordpressErrorThrowException() {
        if ($this->wpDatabaseConnection->last_error != '') {
            $errorMessage = $this->wpDatabaseConnection->last_error;
            throw new WordpressExecutionError($errorMessage);
        }
    }

    public function getWordpressDatabaseObject() {
        global $wpdb;
        return $wpdb;
    }


}

$test = new BaseDataController();
$row = $test->tryToGetSingleRowByQuery("SELECT * FROM Contact WHERE id=200;");

echo $row->readValueForKey('first_nlame');