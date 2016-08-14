<?php
/**
 * Template Name: SucheNeu
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

?>

<?php

// Sortieren
$t_header = array(
		array('value' => 'Contact.last_name', 'name' => 'Nachname'),
		array('value' => 'Contact.first_name', 'name' => 'Vorname'),
		array('value' => 'Contact.birth_date', 'name' => 'Alter'),
		array('value' => 'Ressort.name', 'name' => 'Ressort'),
		array('value' => 'Member.active', 'name' => 'Status'),
		array('value' => 'Contact.id', 'name' => 'ID')
);


// Print Sortieren

// Filter-Data
$ressorts = res_to_array($wpdb->get_results("SELECT name FROM Ressort"));
$positions = res_to_array($wpdb->get_results("SELECT position FROM Member"));
$status = array('0', '1');
$schools = res_to_array($wpdb->get_results("SELECT school FROM Study"));

$filter = array(
		array('name' => 'ressort', 'title' => 'Ressort', 'data' => $ressorts, 'cols' => 2, 'colwidth' =>NULL),
		array('name' => 'position', 'title' => 'HHC Position', 'data' => $positions, 'cols' => 2, 'colwidth' =>NULL),
		array('name' => 'status', 'title' => 'HHC Status', 'data' => $status, 'cols' => 2, 'colwidth' =>NULL),
		array('name' => 'uni', 'title' => 'Universität', 'data' => $schools, 'cols' => 1, 'colwidth' =>NULL)
);


foreach ($filter as $category) {
	$cols = $category['cols'];
	$data = $category['data'];
	$name = $category['name'];
	
	$category['colwidth'] = array('width' => '');
	for ($i=0; $i < $cols; $i++) {
		$count = (count($data)-(count($data)%$cols))/$cols;
		// Problem: doppelte Einträge, wenn $count einen Rest hat
	
// 		$category['colwidth'] = array('width' => );
		
		for ($j=0; $j < $count; $j++) {
			$filter->colvalue[$j] = $data[$j + $i * $count];
		}
	}
}

$data = array('templateDirectory' => get_template_directory(),
			'value_value' => $value[value],
			'value_name' => $value[name],
			't_header' => $t_header,
			'filter' => $filter
);


$mustache = new Mustache_Engine(array(
   'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory().'/templates') 
));

echo $mustache->render('nein', $data);
?>