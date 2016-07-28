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
    echo $result->getValueForKey('first_name');
}
catch (WPDBError $e) {
    echo 'Fehler: ' . $e->getMessage();
}
finally {
    echo '<br>Vielen Dank.<br>';
}

new_paragraph("tryToGetMultipleRowsByQuery");
$it_ressort = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Contact WHERE id<140");

foreach ($it_ressort as $row) {
	print_r($row);
	echo '<br>';
}

new_paragraph("tryToInsertData");
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

new_paragraph('Read new value');
$newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
print_r($newresult);

new_paragraph('Try to update data');
$test->tryToUpdateData(
	'Application',
	array(
		'state' => 'denied'
	),
	array(
		'contact' => 200
	)
);

new_paragraph('Read new value');
$newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
print_r($newresult);

new_paragraph('Delete new value');
$nor = $test->tryToDeleteData(
	'Application',
	array(
		'contact' => 200
	),
	array(
		'%d'
	)
);

echo "$nor rows deleted<br>";
























