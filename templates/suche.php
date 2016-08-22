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






// // Filter-Data
// $ressorts = res_to_array($wpdb->get_results("SELECT name FROM Ressort"));
// $positions = res_to_array($wpdb->get_results("SELECT position FROM Member"));
// $status = array('0', '1');
// $schools = res_to_array($wpdb->get_results("SELECT school FROM Study"));

// $filter = array(
// 	array('name' => 'ressort', 'title' => 'Ressort', 'data' => $ressorts, 'cols' => 2),
// 	array('name' => 'position', 'title' => 'HHC Position', 'data' => $positions, 'cols' => 2),
// 	array('name' => 'status', 'title' => 'HHC Status', 'data' => $status, 'cols' => 2),
// 	array('name' => 'uni', 'title' => 'Universität', 'data' => $schools, 'cols' => 1)
// );


// foreach ($filter as $category) {
// 	$cols = $category['cols'];
// 	$data = $category['data'];
// 	$name = $category['name'];

// 	echo("
// 		<table>
// 			<tr>
// 				<th colspan='$cols'>
// 					".$category['title']."
// 				</th>
// 			</tr>
			
				
// 	");

// 	for ($i=0; $i < $cols; $i++) { 		
// 		$count = count($data)/$cols;
// 		// Problem: doppelte Einträge, wenn $count einen Rest hat

// 		$width = 100/$cols;
// 		echo "<td style='width:".$width."%'><table>";
		
// 		for ($j=0; $j < $count; $j++) { 					
// 			$value = $data[$j + $i * $count];
// 			if($value != '') {
// 				echo "
// 					<tr>
// 						<td width='1px'>
// 							<div class='ui checkbox'>
// 						    	<input 
// 									type='checkbox' 
// 									tabindex='0' 
// 									name='f_".$name."_list[]'
// 									value='$value' 
// 									id='f_".$name."_".$value."'
// 									class='hidden filtercheckbox_".$name."'
// 								>
// 						      <label>".uppercase(bool_to_lbl($value))."</label>
// 						    </div>
// 						</td>

// 					</tr>
// 				";				
// 			}
// 		}
// 		echo "</td></table>";
// 	}
// 	echo "</tr></table>";
// }

// Filter-Data
global $wpdb;
$ressorts = res_to_array($wpdb->get_results("SELECT name FROM Ressort"));
$positions = res_to_array($wpdb->get_results("SELECT position FROM Member"));
$status = array('0', '1');
$schools = res_to_array($wpdb->get_results("SELECT school FROM Study"));



$data = array(
	'root_path' => $root_uri,
	'sort_categories' => $sort_categories,
	'filter_categories' => array(
		array('title' => 'Filter 1', 'filter_items' => array(
			array(
				'width' => 50,
				'rows' => array(
					array('data' => array(
						array('name' => 'name1', 'value' => 'value1', 'display_name' => 'NAME1'),
						array('name' => 'name2', 'value' => 'value2', 'display_name' => 'NAME2')
					)),
					array('data' => array(
						array('name' => 'name3', 'value' => 'value3', 'display_name' => 'NAME3'),
						array('name' => 'name4', 'value' => 'value4', 'display_name' => 'NAME4')
					)),
				)
			)
		)),
		array('title' => 'Filter 2', 'filter_items' => array()),
		array('title' => 'Filter 3', 'filter_items' => array())
	)
);

echo $mustache->render('member_search', $data);

?>