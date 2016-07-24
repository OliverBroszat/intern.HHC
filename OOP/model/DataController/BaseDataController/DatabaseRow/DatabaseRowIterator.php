<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.07.16
 * Time: 22:27
 */

/**
 * Class DatabaseRowIterator
 *
 * Provides reading access to a DatabaseRow's data.
 */
class DatabaseRowIterator implements Iterator {

    private $position = 0;
    private $rowData;

    public function __construct($forDatabaseRow) {
        $this->position = 0;
        $this->rowData = $forDatabaseRow;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->rowData[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->rowData[$this->position]);
    }
}