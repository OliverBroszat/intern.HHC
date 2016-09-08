<?php

class SucheModel extends MustacheData{
private $baseDataController;

public $sort_categories;
public $root_path;
	
// Filter-Data
public $filter_values;
public $filterTitles;
public $filter_list_names;
public $numberOfColumns;
public $filters;
public $currentFilterData;

public function fillFilterData(){
	$this->filters = array();
	
	for ($filterNumber=0; $filterNumber < sizeof($this->filterTitles); $filterNumber++) {
	
		$this->currentFilterData['title'] = $this->filterTitles[$filterNumber];
		$this->currentFilterData['width'] = 100 / $this->numberOfColumns[$filterNumber];
		$this->currentFilterData['list_name'] = $this->filter_list_names[$filterNumber];
		$this->currentFilterData['num_of_columns'] = $this->numberOfColumns[$filterNumber];
		$this->currentFilterData['filter_rows'] = array();
		
	
		$sizeOfRows = $this->numberOfColumns[$filterNumber];
		$numberOfFiltersInCurrentRow = 0;
		$currentFilterValues = $this->filter_values[$filterNumber];
		
		$currentRow = array('row_items' => array());

		foreach ($currentFilterValues as $value) {
			
			$currentItem = array(
					'item_value' => $value,
					'item_display_name' => uppercase(bool_to_lbl($value))
			);
			if ($numberOfFiltersInCurrentRow >= $sizeOfRows) {
				array_push($this->currentFilterData['filter_rows'], $currentRow);
				$currentRow = array('row_items' => array());
				$numberOfFiltersInCurrentRow = 0;
			}
			array_push($currentRow['row_items'], $currentItem);
			$numberOfFiltersInCurrentRow++;
		}
		if (sizeof($currentRow) > 0) {
			array_push($this->currentFilterData['filter_rows'], $currentRow);
		}
		array_push($this->filters, $this->currentFilterData);
	}	
}

public function __construct(){
	parent::__construct();
	
	$this->baseDataController = new BaseDataController();
	
	$this->root_path = get_template_directory_uri();
	
	global $wpdb;
	
	$this->filter_values = array( 			
	  res_to_array($wpdb->get_results("SELECT name FROM Ressort")), 
	  res_to_array($wpdb->get_results("SELECT position FROM Member")), 
	  array('0', '1'), 
	  res_to_array($wpdb->get_results("SELECT school FROM Study")) 
	); 	
	
	$this->sort_categories = array(
			array('value' => 'Contact.last_name', 'name' => 'Nachname'),
			array('value' => 'Contact.first_name', 'name' => 'Vorname'),
			array('value' => 'Contact.birth_date', 'name' => 'Alter'),
			array('value' => 'Ressort.name', 'name' => 'Ressort'),
			array('value' => 'Member.active', 'name' => 'Status'),
			array('value' => 'Contact.id', 'name' => 'ID')
	);
	
	$this->filterTitles = array(
			'Ressort', 'HHC Position', 'HHC Status', 'Universität'
	);
	
	$this->filter_list_names = array(
			'ressort', 'position', 'status', 'uni'
	);
	
	$this->numberOfColumns = array(2, 2, 2, 1);
	
	$this->fillFilterData();
}
}