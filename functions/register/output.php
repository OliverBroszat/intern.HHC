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


get_header();

$status = $_GET['status'];

if ($status == 'ok'){
	echo "<div class='msg ok'>Vielen Dank!<br>Deine Daten wurden übernommen!</div>";
}
else{
	echo "<div class='msg'>Leider Hat die Dateneingabe nicht geklappt. Hast du alle Pflichtfelder ausgefüllt?<br>Bitte gehe zurück und versuche es noch einmal</div>";
}
?>