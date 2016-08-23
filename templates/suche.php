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
$viewpath = $root_uri.'/views';

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
global $wpdb;
$filter_values = array(
	res_to_array($wpdb->get_results("SELECT name FROM Ressort")),
	res_to_array($wpdb->get_results("SELECT position FROM Member")),
	array('0', '1'),
	res_to_array($wpdb->get_results("SELECT school FROM Study"))
);
$filterTitles = array(
	'Ressort', 'HHC Position', 'HHC Status', 'Universität'
);
$filter_list_names = array(
	'ressort', 'position', 'status', 'uni'
);
$numberOfColumns = array(2, 2, 2, 1);

$filters = array();
for ($filterNumber=0; $filterNumber < sizeof($filterTitles); $filterNumber++) {
	$currentFilterData = array();
	$currentFilterData['title'] = $filterTitles[$filterNumber];
	$currentFilterData['width'] = 100 / $numberOfColumns[$filterNumber];
	$currentFilterData['list_name'] = $filter_list_names[$filterNumber];
	$currentFilterData['num_of_columns'] = $numberOfColumns[$filterNumber];

	$currentFilterData['filter_rows'] = array();
	$sizeOfRows = $numberOfColumns[$filterNumber];
	$numberOfFiltersInCurrentRow = 0;
	$currentFilterValues = $filter_values[$filterNumber];
	$currentRow = array('row_items' => array());
	foreach ($currentFilterValues as $value) {
		$currentItem = array(
			'item_value' => $value,
			'item_display_name' => uppercase(bool_to_lbl($value))
		);
		if ($numberOfFiltersInCurrentRow >= $sizeOfRows) {
			array_push($currentFilterData['filter_rows'], $currentRow);
			$currentRow = array('row_items' => array());
			$numberOfFiltersInCurrentRow = 0;
		}
		array_push($currentRow['row_items'], $currentItem);
		$numberOfFiltersInCurrentRow++;
	}
	if (sizeof($currentRow) > 0) {
		array_push($currentFilterData['filter_rows'], $currentRow);
	}
	array_push($filters, $currentFilterData);
}

// $filters = array(
// 	array(
// 		'title' => 'Filter 1',
// 		'cols' => 2,
// 		'col_width' => 50,
// 		'filter_name' => 'inID',
// 		'filter_items' => array(
// 			array(
// 				'rows' => array(
// 					array('data' => array(
// 						array('value' => 'value1', 'display_name' => 'NAME1'),
// 						array('value' => 'value2', 'display_name' => 'NAME2')
// 					)),
// 					array('data' => array(
// 						array('value' => 'value3', 'display_name' => 'NAME3'),
// 						array('value' => 'value4', 'display_name' => 'NAME4')
// 					)),
// 				)
// 			)
// 		)
// 	),
// 	array('title' => 'Filter 2', 'filter_items' => array()),
// 	array('title' => 'Filter 3', 'filter_items' => array())
// );

$data = array(
	'root_path' => $root_uri,
	'sort_categories' => $sort_categories,
	'filters' => $filters
);

echo $mustache->render('member_search', $data);

?>