<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.07.16
 * Time: 23:36
 */

/**
 * Class DatabaseRow
 *
 * Represents one database row, provides OO access to data and exception handling with rows
 */
class DatabaseRow
{

    private $dataRow;

    public function __construct($dataRow)
    {
        $this->dataRow = $dataRow;
    }

    /**
     * @param $key
     * @return bool
     */
    public function rowContainsKey($key)
    {
        if ($this->dataRow->$key == null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $key
     * @return null
     */
    public function readValueForKey($key)
    {
        if ($this->rowContainsKey($key)) {
            return $this->dataRow->$key;
        } else {
            throw new ValueError("The requested key '$key' does not exist");
        }
    }
}