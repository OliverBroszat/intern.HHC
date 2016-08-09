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
            $errorMessage = "Wordpress database object is null in BaseDataController::__contruct()";
            throw new WPDBError($errorMessage);
        }
    }

    public function print_msg($msg) {
        echo "$msg<br>";
    }


    public function tryToSelectSingleRowByQuery($sqlQuery) {
        $selectedRows = $this->tryToSelectMultipleRowsByQuery($sqlQuery);
        if (sizeof($selectedRows) != 1) {
            $errorMessage = "More than 1 row was selected by SQL query '$sqlQuery' in BaseDataController::tryToSelectSingleRowByQuery()";
            throw new LengthException($errorMessage);
        }
        return $selectedRows[0];
    }

    public function tryToSelectMultipleRowsByQuery($sqlQuery) {
        $requestedRowsUnwrapped = $this->wpDatabaseConnection->get_results($sqlQuery);
        $this->onWordpressErrorThrowException();
        $requestedRowsWrapped = $this->wrapSQLResultsIntoDatabaseRows($requestedRowsUnwrapped);
        return $requestedRowsWrapped;
    }

    public function tryToInsertData($table, $dataToInsert, $dataFormat=null) {
        $numberOfAffectedRows = $this->wpDatabaseConnection->insert($table, $dataToInsert, $dataFormat);
        $this->onWordpressErrorThrowException();
        // TODO: $numberOfAffectedRows müsste an dieser Stelle 1
        // sein. Sollte hier mittels assert getestet werden
        return $numberOfAffectedRows;
    }

    public function tryToInsertRow($table, $row) {
        $dataArray = $row->toArray();
        $this->tryToInsertData(
            $table,
            $insertArray,
            null
        );
    }

    /**
    * tryToInsertRowWithAutoUpdateSinglePrimary
    * 
    * Insert data from a given DatabaseRow object that has one auto-increment
    * integer primary key. After insertion, the primary value will be updated
    * automatically.
    *
    * @param String         $table      The table to insert the DatabaseRow
    * @param DatabaseRow    $row        DatabaseRow object containing data to insert
    * 
    * @return void
    */
    public function tryToInsertRowWithAutoUpdateSingleAutoPrimary($table, $row) {
        // TODO: also check for data type like auto-inc INT

        $this->throwExceptionOnMultiplePrimaryColumnsForTable($table);
        $columnsToUnset = $this->getPrimaryColumnNamesForTable($table);
        $row->deleteMultipleColumnsWithName($columnsToUnset);
        $this->tryToInsertRow($table, $row);
        // We can be sure, there is exactly one primary auto-inc INT key
        $nameOfPrimaryKey = $this->getPrimaryColumnNamesForTable($table)[0];
        $row->setValueForKey($nameOfPrimaryKey, $this->getIdFromLastInsert());
    }

    private function throwExceptionOnMultiplePrimaryColumnsForTable($table) {
        $primaryColumns = $this->getPrimaryColumnNamesForTable($table);
        echo 'XXXXXXXXXXXXXXXXXXXXX<br><br>';
        var_dump($primaryColumns);
        $numberOfPrimaryColumns = count($primaryColumns);
        if ($numberOfPrimaryColumns != 1) {
            $errorMessage = "Table '$table' must have one primary column but has $numberOfPrimaryColumns";
            throw new InvalidArgumentException($errorMessage);
        }
    }

    public function tryToUpdateData($table, $dataToUpdate, $sqlWhereStatement, $updateDataFormat=null, $whereFormat=null) {
        $numberOfAffectedRows = $this->wpDatabaseConnection->update($table, $dataToUpdate, $sqlWhereStatement, $updateDataFormat, $whereFormat);
        $this->onWordpressErrorThrowException();
        if ($numberOfAffectedRows == false) {
            $readableDataToUpdate = json_encode($dataToUpdate);
            $readableSqlWhereStatement = json_encode($sqlWhereStatement);
            $readableUpdateDataFormat = json_encode($updateDataFormat);
            $readableWhereFormat = json_encode($whereFormat);
            throw new InvalidArgumentException("No rows were updated in table '$table' with update data '$readableDataToUpdate', where statement '$readableWhereStatement', data format '$readableUpdateDataFormat' and where format '$readableWhereFormat'");
        }
    }

    public function tryToUpdateRowInTable($row, $table) {
        $dataArray = $row->toArray();
        $tablePrimaries = $this->getPrimaryColumnNamesForTable($table);
        //$whereArray = array_filter($dataArray, function ($name) {return in_array($name, $tablePrimaries);});
        $whereArray = array();
        foreach ($dataArray as $key => $value) {
            if (in_array($key, $tablePrimaries)) {
                $whereArray[$key] = $value;
            }
        }
        // TODO: Add datatypes. How to?
        $dataFormatArray = null;
        $whereFormatArray = null;
        $this->tryToUpdateData($table, $dataArray, $whereArray, $dataFormatArray, $whereFormatArray);
    }

    public function getColumnNamesForTable($table) {
        $sqlQuery = "SHOW COLUMNS FROM $table";
        $columnNameResults = $this->tryToSelectMultipleRowsByQuery($sqlQuery);
        $filteredColumnNames = $this->filterValuesFromRowsForSingleKey(
            'Field',
            $columnNameResults
        );
        return $filteredColumnNames;
    }

    public function getPrimaryColumnNamesForTable($table) {
        $sqlQuery = "SHOW KEYS FROM $table WHERE Key_name='PRIMARY';";
        $columnNameResults = $this->tryToSelectMultipleRowsByQuery($sqlQuery);
        $filteredColumnNames = DatabaseRow::filterValuesFromRowsForSingleKey(
            'Column_name',
            $columnNameResults
        );
        return $filteredColumnNames;
    }

    public function getNonPrimaryColumnNamesForTable($table) {
        $valuesToInsert = array_diff(
            $this->getColumnNamesForTable($table),
            $this->getPrimaryColumnNamesForTable($table)
        );
        return $valuesToInsert;
    }

    private function getPrimaryDataArrayForRow($row, $table) {
        $primaryKeys = $this->getPrimaryColumnNamesForTable($table);
        var_dump($row);
        echo '<br>';
        var_dump($primaryKeys);
        $valuesOfPrimaryKeys = array_map(
            function ($key) { global $row; return $row->getValueForKey($key); },
            $primaryKeys
        );
        return array_combine($primaryKeys, $valuesOfPrimaryKeys);
    }

    public function tryToDeleteData($table, $sqlWhereStatement, $whereFormat=null) {
        $numberOfAffectedRows = $this->wpDatabaseConnection->delete($table, $sqlWhereStatement, $whereFormat);
        $this->onWordpressErrorThrowException();
        // When there is a wp internal error (wrong parameter type etc), then $wpdb->last_error is not set i.e. not catched by onWordpressErrorThrowException(). However, in case of such an error, the delete function only returns false
        if ($numberOfAffectedRows == false) {
            $readableSqlWhereStatement = json_encode($sqlWhereStatement);
            $readableWhereFormat = json_encode($whereFormat);
            throw new InvalidArgumentException("No row was deleted in table '$table' with where statement '$readableSqlWhereStatement' and where format '$readableWhereFormat'");
        }
    }

    public function prepareSqlQuery($query, $args) {
        return $this->wpDatabaseConnection->prepare($query, $args);
    }

    public function getIdFromLastInsert() {
        $insertId = $this->wpDatabaseConnection->insert_id;
        if ($insertId == false) {
            $errorMessage = 'No id from last SQL insert statement could be found';
            throw RuntimeException($errorMessage);
        }
        else {
            return $insertId;
        }
    }

    private static function wrapSQLResultsIntoDatabaseRows($unwrappedRows) {
        $wrappedRows = array();
        foreach ($unwrappedRows as $row) {
            array_push($wrappedRows, new DatabaseRow($row));
        }
        return $wrappedRows;
    }

    private function onWordpressErrorThrowException() {
        $lastWordpressError = $this->wpDatabaseConnection->last_error;
        if ($lastWordpressError != '') {
            throw new WPDBError($lastWordpressError);
        }
    }

    private function getWordpressDatabaseObject() {
        global $wpdb;
        return $wpdb;
    }

}
