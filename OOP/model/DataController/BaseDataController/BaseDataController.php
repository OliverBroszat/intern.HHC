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

    // Völlig in Ordnung
    public function selectSingleRowByQuery($sqlQuery) {
        $selectedRows = $this->selectMultipleRowsByQuery($sqlQuery);
        if (sizeof($selectedRows) != 1) {
            $errorMessage = "More than 1 row was selected by SQL query '$sqlQuery' in BaseDataController::tryToSelectSingleRowByQuery()";
            throw new LengthException($errorMessage);
        }
        return $selectedRows[0];
    }

    // Völlig okay
    public function selectMultipleRowsByQuery($sqlQuery) {
        $requestedRowsUnwrapped = $this->wpDatabaseConnection->get_results($sqlQuery);
        $this->onWordpressErrorThrowException();
        $requestedRowsWrapped = $this->wrapSQLResultsIntoDatabaseRows($requestedRowsUnwrapped);
        return $requestedRowsWrapped;
    }

    public function selectSingleRowByIDInTable($ID, $table) {
        $query = "SELECT * FROM $table WHERE id=$ID";
        return $this->selectSingleRowByQuery($query);
    }

    public function selectMultipleRowsByIDInTable($IDs, $table) {
        $rows = array();
        foreach ($IDs as $ID) {
            array_push($rows, $this->selectSingleRowByIDInTable($ID, $table));
        }
        return $rows;
    }

    // Schnittstelle zur WP Anbindung
    private function insertData($table, $dataToInsert, $dataFormat=null) {
        $numberOfAffectedRows = $this->wpDatabaseConnection->insert($table, $dataToInsert, $dataFormat);
        $this->onWordpressErrorThrowException();
        // TODO: $numberOfAffectedRows müsste an dieser Stelle 1
        // sein. Sollte hier mittels assert getestet werden
        return $numberOfAffectedRows;
    }

    /*
    NOTE: ID wird nicht automatisch gesetzt!!!
    */
    public function insertSingleRowInTable($row, $table) {
            $dataArray = $row->toArray();
        $this->insertData(
            $table,
            $dataArray,
            null
        );
    }

    /*
    NOTE: ID wird nicht automatisch gesetzt!!!
    */
    public function insertMultipleRowsInTable($rows, $table) {

        foreach ($rows as $row) {

            $this->insertSingleRowInTable($row, $table);
        }
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
    public function insertSingleRowWithAutoUpdateSingleAutoPrimaryInTable($row, $table) {
        // TODO: also check for data type like auto-inc INT
        $this->throwExceptionOnMultiplePrimaryColumnsForTable($table);
        $columnsToUnset = $this->getPrimaryColumnNamesForTable($table);
        $row->deleteMultipleColumnsWithName($columnsToUnset);
        $this->insertSingleRowInTable($row, $table);
        // We can be sure, there is exactly one primary auto-inc INT key
        $nameOfPrimaryKey = $this->getPrimaryColumnNamesForTable($table)[0];
        $row->setValueForKey($nameOfPrimaryKey, $this->getIdFromLastInsert());
    }

    public function insertMultipleRowsWithAutoUpdateSingleAutoPrimaryInTable($rows, $table) {
        foreach ($rows as $row) {
            $this->insertSingleRowWithAutoUpdateSingleAutoPrimaryInTable($row, $table);
        }
    }

    // Prüft, ob eine Tabelle nur einen einzigen Primary Key hat
    private function throwExceptionOnMultiplePrimaryColumnsForTable($table) {
        $primaryColumns = $this->getPrimaryColumnNamesForTable($table);
        $numberOfPrimaryColumns = count($primaryColumns);
        if ($numberOfPrimaryColumns != 1) {
            $errorMessage = "Table '$table' must have one primary column but has $numberOfPrimaryColumns";
            throw new InvalidArgumentException($errorMessage);
        }
    }

    // Schnittstellenfunktion zu Wordpress
    private function updateData($table, $dataToUpdate, $sqlWhereStatement, $updateDataFormat=null, $whereFormat=null) {
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

    // UPDATE
    // Nimmt eine Row und bereitet alles für den Wordpress-Style UPDATE vor
    // geht davon aus, dass ALLE Daten für den Update korrekt sind!
    public function updateSingleRowInTable($row, $table) {
        $dataArray = $row->toArray();
        $whereArray = $this->getPrimaryDataArrayForRowInTable($row, $table);
        // TODO: Add datatypes. How to?
        $dataFormatArray = null;
        $whereFormatArray = null;
        $this->updateData($table, $dataArray, $whereArray, $dataFormatArray, $whereFormatArray);
    }

    public function updateMultipleRowsInTable($rows, $table) {
        foreach ($rows as $row) {
            $this->updateSingleRowInTable($row, $table);
        }
    }

    // Funktionen um Namen 
    public function getColumnNamesForTable($table) {
        $sqlQuery = "SHOW COLUMNS FROM $table";
        $columnNameResults = $this->selectMultipleRowsByQuery($sqlQuery);
        $filteredColumnNames = $this->filterValuesFromRowsForSingleKey(
            'Field',
            $columnNameResults
        );
        return $filteredColumnNames;
    }

    public function getPrimaryColumnNamesForTable($table) {
        $sqlQuery = "SHOW KEYS FROM $table WHERE Key_name='PRIMARY';";
        $columnNameResults = $this->selectMultipleRowsByQuery($sqlQuery);
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

    // Gibt ein Array aller Prmarschlüssel mit ihren jeweiligen Werten aus einer Row zurück
    // Wofür brauche ich das?
    public function getPrimaryDataArrayForRowInTable($row, $table) {
        $tablePrimaries = $this->getPrimaryColumnNamesForTable($table);
        $dataArray = array();
        foreach ($tablePrimaries as $key) {
            $dataArray[$key] = $row->getValueForKey($key);
        }
        return $dataArray;
    }

    // Delete Schnittstellenfunktion für Wordpress-Style Delete
    private function deleteData($table, $sqlWhereStatement, $whereFormat=null) {
        $numberOfAffectedRows = $this->wpDatabaseConnection->delete($table, $sqlWhereStatement, $whereFormat);
        $this->onWordpressErrorThrowException();
        // When there is a wp internal error (wrong parameter type etc), then $wpdb->last_error is not set i.e. not catched by onWordpressErrorThrowException(). However, in case of such an error, the delete function only returns false
        if ($numberOfAffectedRows == false) {
            $readableSqlWhereStatement = json_encode($sqlWhereStatement);
            $readableWhereFormat = json_encode($whereFormat);
            throw new InvalidArgumentException("No row was deleted in table '$table' with where statement '$readableSqlWhereStatement' and where format '$readableWhereFormat'");
        }
    }

    public function deleteSingleRowFromTable($row, $table) {
        // TODO: No data types for whereFormat
        $whereArray = $this->getPrimaryDataArrayForRowInTable($row, $table);
        $this->deleteData($table, $whereArray, null); // <<< !!! null shouldn't be there!
    }

    public function deleteMultipleRowsFromTable($rows, $table) {
        foreach ($rows as $row) {
            $this->deleteSingleRowFromTable($row, $table);
        }
    }

    public function deleteSingleRowFromTableByID($table, $ID) {
        $row = $this->selectSingleRowByIDInTable($ID, $table);
        $this->deleteSingleRowFromTable($row, $table);
    }

    public function deleteMultipleRowsFromTableByID($table, $IDs) {
        foreach ($IDs as $ID) {
            $this->deleteSingleRowFromTableByID($table, $ID);
        }
    }

    // Schon im BaseDataController vorhanden!!!!!
    // Löschen
    public function prepareSqlQuery($query, $args) {
        return $this->wpDatabaseConnection->prepare($query, $args);
    }

    public function getIdFromLastInsert() {
        $insertId = $this->wpDatabaseConnection->insert_id;
        if ($insertId == false) {
            $errorMessage = 'No id from last SQL insert statement could be found';
            throw new RuntimeException($errorMessage);
        }
        else {
            return $insertId;
        }
    }

    // Wird gebraucht, wenn SQL results von wordpress geholt werden
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
