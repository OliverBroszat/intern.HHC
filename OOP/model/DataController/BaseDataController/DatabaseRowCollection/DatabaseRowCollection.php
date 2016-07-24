<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.07.16
 * Time: 22:08
 */

/**
 * Class DatabaseRowCollection
 *
 * Stores DatabaseRow objects. Provides ArrayAccess and some functionality that might come in handy.
 */
class DatabaseRowCollection {

    private $databaseRows;

    /**
     * DatabaseRowCollection constructor.
     * @param $databaseRows
     */
    public function __construct($databaseRows) {
        $this->databaseRows = $databaseRows;
    }

    // TODO: ---------------------------------------------------------------------------
    // TODO: Move 'generateHTML...' methods to view/DatabaseView/RowCollectionView.php

    /**
     * @return string
     *
     * Returns valid HTML that contains a table which displays the RowCollection's data.
     */
    public function generateHTMLTable() {
        $htmlCode = '<table>' . $this->generateHTMLRows() . '</table>';
        return $htmlCode;
    }

    /**
     * @return string
     *
     * Returns HTML that contains table rows which display the RowCollection's data.
     * Can be used to assemble multiple row collections into one table.
     */
    public function generateHTMLRows() {
        $htmlCode = '';
        foreach ($this->databaseRows as $databaseRow) {
            $htmlCode .= $databaseRow->generateHTMLRow();
        }
        return $htmlCode;
    }

    // TODO: ---------------------------------------------------------------------------

    /**
     * @return bool
     */
    public function isEmpty() {
        return empty($this->databaseRows);
    }

    public function getDatabaseRowCollectionIterator() {
        return new DatabaseRowCollectionIterator($this);
    }

}