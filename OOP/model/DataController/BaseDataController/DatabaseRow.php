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
 */
class DatabaseRow
{

    private $sqlQueryResult;

    public function __construct($sqlQueryResult)
    {
        $this->sqlQueryResult = $sqlQueryResult;
    }

    public function getValueForKey($key)
    {
        $valueForKey = $this->sqlQueryResult->$key;
        if ($valueForKey != null) {
            return $valueForKey;
        } else {
            throw new InvalidArgumentException("The requested key '$key' does not exist");
        }
    }

    // TODO: Move to view/DatabaseView/???.php !!!
    public function generateHTMLTable() {
        $html = '<table>' . $this->generatelHTMLRow() . '</table>';
    }

    // TODO: Move to view/DatabaseView/???.php !!!
    public function generateHTMLRow() {
        $htmlCode = '<tr>';
        foreach ($this->dataRow as $value) {
            $htmlCode .= "<td>$value</td>";
        }
        $htmlCode .= '</tr>';
        return $htmlCode;
    }
}