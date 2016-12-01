<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.07.16
 * Time: 23:36
 */

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


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

    public function childsToArray() {
        $dataArray = array();
        $indices = $this->getColumnNames();
        foreach ($indices as $index) {
           $columnNames = $index->getColumnNames();
           foreach ($index as $key => $value) {
                $dataArray[$index][$columnName] = $this->getValueForKey($key);
           }
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
