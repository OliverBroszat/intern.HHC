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

get_header();

?>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
<?php

new_paragraph("DataControllerTest");
$test = new BaseDataController();

new_paragraph("tryToGetSingleRowByQuery");
try {
    $result = $test->selectSingleRowByQuery("SELECT * FROM Contact WHERE id=200;");
    echo $result->getValueForKey('first_name');
    $test->getPrimaryDataArrayForRowInTable($result, 'Contact');
}
catch (WPDBError $e) {
    echo 'Fehler: ' . $e->getMessage();
}
finally {
    echo '<br>Vielen Dank.<br>';
}

new_paragraph("tryToGetMultipleRowsByQuery");
$it_ressort = $test->selectMultipleRowsByQuery("SELECT * FROM Contact WHERE id<140");

foreach ($it_ressort as $row) {
	print_r($row);
	echo '<br>';
}

// new_paragraph("tryToInsertData");
// $nor = $test->tryToInsertData(
// 	'Application',
// 	array(
// 		'contact' => 200,
// 		'income' => '2016-10-10',
// 		'state' => 'new',
// 		'assessment_template' => 1
// 	),
// 	array(
// 		'%d', '%s', '%s', '%d'
// 	)
// );

// new_paragraph('Read new value');
// $newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
// print_r($newresult);

// new_paragraph('Try to update data');
// $test->tryToUpdateData(
// 	'Application',
// 	array(
// 		'state' => 'denied'
// 	),
// 	array(
// 		'contact' => 200
// 	)
// );

// new_paragraph('Read new value');
// $newresult = $test->tryToSelectMultipleRowsByQuery("SELECT * FROM Application WHERE contact=200");
// print_r($newresult);

// new_paragraph('Delete new value');
// $nor = $test->tryToDeleteData(
// 	'Application',
// 	array(
// 		'contact' => 200
// 	),
// 	array(
// 		'%d'
// 	)
// );

// echo "$nor rows deleted<br>";

new_paragraph("Get ContactProfile");
$userC = new ContactDataController(null, $test);
$profile = $userC->getSingleContactProfileByID(200);
print_r($profile);
echo '<br><br>';
echo $profile->contactDatabaseRow->getValueForKey('first_name');
echo '<br>'.$profile->mailDatabaseRows[0]->getValueForKey('address');


new_paragraph("Get more ContactProfiles");
$profiles = $userC->getMultipleContactProfilesByID(
	array(
		200,
		201,
		202
	)
);
foreach ($profiles as $p) {
	print_r($p->contactDatabaseRow->getValueForKey('last_name'));
	echo '<br>';
	print_r($p->addressDatabaseRows);
	echo '<br>';
	print_r($p->mailDatabaseRows);
	echo '<br>';
	print_r($p->phoneDatabaseRows);
	echo '<br>';
	print_r($p->studyDatabaseRows);
	echo '<br><br>';
}
echo $profiles[2]->addressDatabaseRows[0]->getValueForKey('street');

new_paragraph('Update existing Contact by Row');
$p = $profiles[2];
$p->contactDatabaseRow->setValueForKey('last_name', 'TEST2');
try {
	$test->updateSingleRowInTable($p->contactDatabaseRow, 'Contact');
}
catch (InvalidArgumentException $e) {
	// No Row updated. Ignore that Case
}

new_paragraph('Create a Contact');
$newprofile = $profiles[2];

$contact = array(
	'prefix' => 'Herr',
	'first_name' => "Peter",
	'last_name' => "Becker",
	'birth_date' => "1993-01-01",
	'comment' => '',
	'skype_name' => null
);
$contact = new DatabaseRow((object) $contact);
$addresses = array(); //new DatabaseRow((object) array());
$mails = array(); //new DatabaseRow((object) array());
$phones = array(); //new DatabaseRow((object) array());
$studies = array(); //new DatabaseRow((object) array());
$verynewProfile = new ContactProfile(
	$contact,
	$addresses,
	$mails,
	$phones,
	$studies
);
echo '***************vvv*********<br><br>';
echo '<br><br>*******************zuzuzuz*****<br><br>';
$userC->createSingleContactByProfile($newprofile);
echo '************************<br><br>';
var_dump($verynewProfile);
echo '<br><br>***************yyyyyyy*********<br><br>';

$verynewProfile->contactDatabaseRow->setValueForKey('first_name', 'Hermann');
$userC->createSingleContactByProfile($verynewProfile);
var_dump($verynewProfile);

new_paragraph('Delete a Contact');
//$userC->deleteSingleContactByID($newprofile->contactDatabaseRow->getValueForKey('id'));

new_paragraph('getNamesOfColumns');
print_r($verynewProfile->contactDatabaseRow->getColumnNames());

// $memberC = new MemberDataController(null, $userC);
// new_paragraph('Get MemberProfile');
// $p1 = $memberC->getSingleMemberProfileByContactID(135);
// var_dump($p1);

// new_paragraph('MemberController Test');
// $member_array = array(
// 	'contact' => $newprofile->contactDatabaseRow->getValueForKey('id'),
// 	'ressort' => 1,
// 	'active' => 0,
// 	'position' => 'mitglied',
// 	'joined' => '2016-08-07',
// 	'left' => '0000-00-00'
// );
// $member_object = (object) $member_array;
// $member_row = new DatabaseRow($member_object);
// $memberProfile = new MemberProfile($member_row, $newprofile);
// $memberC->createSingleMemberByProfile($memberProfile);

?>

	</div>
</div>













