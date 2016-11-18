<?php
/**
 * Template Name: Search OOP
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

// TEST CODE!

function new_paragraph($headline) {
    echo "<h1>$headline</h1>";
}

function new_sub_h($h) {
    echo "<h2>$h</h2>";
}

get_header();

?>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">

<?php

new_paragraph("SearchDataController");

$filters = new FilterLists();

foreach ($filters->getSearchFilter() as $filter) {
	$filter->createHtmlTable();
}

$post = array(
	'sort' => 'Contact.id',
	'order' => 'asc',
	'search_text' => 'daniel',
	'filter_Ressort-name' => array(),
	'filter_Member-position' => array(),
	'filter_Member-active' => array(),
	'filter_Study-school' => array()
);

$searchController = new searchController($post);


new_sub_h('Search Data');
arr_to_list($searchController->getSearchData());


new_sub_h('Search');

echo "<h3>Member Profiles</h3>";

$memberProfiles = $searchController->search();

foreach ($memberProfiles as $memberProfile) {
	echo "
		{$memberProfile->contactProfile->contactDatabaseRow->getValueForKey('first_name')} 
		{$memberProfile->contactProfile->contactDatabaseRow->getValueForKey('last_name')}, 
		{$memberProfile->memberDatabaseRow->getValueForKey('contact')}
	";
	echo "<br>";
}



// activate semantic ui checkboxes
echo "<script> $('.ui.checkbox').checkbox(); </script> ";
