<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();
$root_uri = get_template_directory_uri();
$viewpath = $root_uri.'/views/search';

$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(
    	$viewpath,
    	array('extension' => '.html')
    )
));

$sort_categories = array(
	array('value' => 'Contact.last_name', 'name' => 'Nachname'),
	array('value' => 'Contact.first_name', 'name' => 'Vorname'),
	array('value' => 'Contact.birth_date', 'name' => 'Alter'),
	array('value' => 'Ressort.name', 'name' => 'Ressort'),
	array('value' => 'Member.active', 'name' => 'Status'),
	array('value' => 'Contact.id', 'name' => 'ID')
);


// Filter-Data
$filters = new FilterLists();
$filtersHTML = array();
foreach ($filters->getSearchFilter() as $filter) {
	array_push($filtersHTML, $filter->createHtmlTable());
}

$data = array(
	'root_path' => $root_uri,
	'sort_categories' => $sort_categories,
	'filters' => $filtersHTML
);

echo $mustache->render('member_search', $data);

?>