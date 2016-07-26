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
$test = new BaseDataController();

new_paragraph("tryToGetSingleRowByQuery");
try {
    $result = $test->tryToSelectSingleRowByQuery("SELECT * FROM Contact WHERE id=200;");
    echo $result->first_name;
}
catch (WPDBError $e) {
    echo 'Fehler: ' . $e->getMessage();
}
finally {
    echo '<br>Vielen Dank.<br>';
}

new_paragraph("tryToGetRowCollectionByQuery Iterator");
$it_ressort = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Contact WHERE id<140");

foreach ($it_ressort as $row) {
	print_r($row);
	echo '<br>';
}

new_paragraph("tryToGetRowCollectionByQuery HTML Table");
echo $it_ressort->generateHTMLTable();
