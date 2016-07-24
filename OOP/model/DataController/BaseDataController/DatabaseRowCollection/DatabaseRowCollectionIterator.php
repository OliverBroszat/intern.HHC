<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.07.16
 * Time: 22:27
 */

/**
 * Class DatabaseRowCollectionIterator
 *
 * Provides reading access to a DatabaseRowCollection's rows.
 */
class DatabaseRowCollectionIterator implements Iterator {

    private $position = 0;
    private $rowCollection;

    public function __construct($forDatabaseRowCollection) {
        $this->position = 0;
        $this->rowCollection = $forDatabaseRowCollection;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->rowCollection[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->rowCollection[$this->position]);
    }
}