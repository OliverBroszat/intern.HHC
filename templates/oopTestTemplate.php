<?php
/**
 * Template Name: OOP Test
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// TEST CODE!

function new_paragraph($headline) {
    echo "<h1>$headline</h1>";
}

new_paragraph("DataControllerTest");
new_paragraph("tryToGetSingleRowByQuery");

$test = new BaseDataController();
try {
    $result = $test->tryToGetSingleRowByQuery("SELECT * FROM Contact WHERE id=200;");
    echo $result->readValueForKey('first_name');
}
catch (WordpressExecutionError $e) {
    echo 'Fehler: ' . $e->getMessage();
}
catch (WordpressConnectionError $e) {
    echo 'Kein Internet';
}
finally {
    echo '<br>Vielen Dank.<br>';
}

new_paragraph("tryToGetRowCollectionByQuery");
$it_ressort = $test->tryToGetRowCollectionByQuery("SELECT * FROM Contact WHERE id<140");
echo $it_ressort->generateHTMLTable();