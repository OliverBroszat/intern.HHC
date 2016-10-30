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

			
				<input type="hidden" disabled name="templateDirectory" id="templateDirectory" value="<?php echo get_template_directory_uri(); ?>">

				<button type='button' class='fluid ui labeled icon basic button' onclick="ajax_post();">
					<i class="refresh icon"></i>
					Aktualisieren
				</button>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	</form>

	<main class="container">
		<div class="ui segment actions">					

			<button type='button' class='ui labeled icon basic button' id='new-entry' value='new' onclick="edit('new');">
				<i class="file outline icon"></i>
				Neu
			</button>

			<button type='button' class='ui labeled icon basic button' onclick='edit_multi()'>
				<i class="edit icon"></i>
				Edit selected
			</button>

			<button type='button' class='ui labeled icon basic button' onclick='select_all()'>
				<i class="checkmark box icon"></i>
				Select/Deselect all
			</button>
			
			<script src='<?php echo get_template_directory_uri(); ?>/js/ajax_download_csv.js'></script>

			<button type='button' class='ui labeled icon basic button' onclick="download_csv()">
				<i class="download icon"></i>
				Download CSV
			</button>

		</div><!-- /panel -->

<!--  Suchergebnisse -->
		<div class='ui segment' id='search-results'>			
			<h2 id='search-results-title'>Suchergebnisse (0)</h2>
			<div id='list-container'></div>			
		</div><!-- /panel -->
	</main>
	
</div><!-- /outer -->


<!-- Semantic UI -->
<script>
	$('.ui.checkbox').checkbox();
	$('.ui.dropdown').dropdown();
</script>

<!-- Import ajax_post() function -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search.js"></script>

<!-- Call AJAX Search on page load -->
<script>window.onload=ajax_post;</script>

<!-- Call AJAX Search on #form-suche submit -->
<script>
	$("#form-suche").submit(function(e){
	    e.preventDefault();
	    $("#start-search").focus();
	    ajax_post();

	});
</script>

<!-- AJAX Search Suggestions -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search_suggestions.js"></script>

 <!-- AJAX Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_edit.js"></script> 

<!-- Multi-Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_edit_multi.js"></script> 

<!-- Expand Detail Content -->
<script>
	function expand_content(value){
		$('#slide_content_show_detail_'+value).slideToggle(300);
		$('#slide-content-button-'+value+' i').toggleClass('down');
	}
</script>



<?php get_footer(); ?>