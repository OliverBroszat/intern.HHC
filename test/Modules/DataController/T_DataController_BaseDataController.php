<?php

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

// I can be sure, that I am in a well suited environment
// All modules are imported, I got all wp functions and
// the auto loader is set up
// phpUnit is also loaded!
// Everything is just fine :) Now test!

class T_DataController_BaseDataController extends TestCase {

	protected $information = "This is my first test case";

    public function getResultsFromTest() {
        $test = new BaseDataController();

        $this->debug("tryToGetSingleRowByQuery");
        try {
            $result = $test->selectSingleRowByQuery("SELECT * FROM Contact WHERE id=200;");
            $test_id = $result->getValueForKey('id');
            if ($test_id == 200) {
                echo 'Passed<br>';
            }
            else {
                echo "ID $test_id doesnt match expected value 200<br>";
            }
        }
        catch (WPDBError $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $this->debug("tryToGetMultipleRowsByQuery");
        $it_ressort = $test->selectMultipleRowsByQuery("SELECT * FROM Contact WHERE id<140");

        foreach ($it_ressort as $row) {
            print_r($row);
            echo '<br>';
        }

        $this->debug("tryToInsertData");
        $nor = $test->tryToInsertData(
            'Application',
            array(
                'contact' => 200,
                'income' => '2016-10-10',
                'state' => 'new',
                'assessment_template' => 1
            ),
            array(
                '%d', '%s', '%s', '%d'
            )
        );

        $this->debug('Read new value');
        $newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
        print_r($newresult);

        $this->debug('Try to update data');
        $test->tryToUpdateData(
         'Application',
         array(
             'state' => 'denied'
         ),
         array(
             'contact' => 200
         )
        );

        $this->debug('Read new value');
        $newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
        print_r($newresult);

        $this->debug('Delete new value');
        $nor = $test->tryToDeleteData(
         'Application',
         array(
             'contact' => 200
         ),
         array(
             '%d'
         )
        );
    }

}

if(str_replace('\\', '/', __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    if ($_GET['command'] == 'run') {
        $test = new T_DataController_BaseDataController();
        echo $test->getResultsFromTest();
    }
}

?>