<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');


class Filter {

	private $filterName;
	private $table;
	private $column;
	private $columnCount;
	private $options;


	public function __construct($filterName, $table, $column, $columnCount = 1) {
		$this->filterName = $filterName;
		$this->table = $table;
		$this->column = $column;
		$this->columnCount = $columnCount;

		$base = new BaseDataController();
		$optionsRows = $base->selectMultipleRowsByQuery("SELECT DISTINCT {$this->column} FROM {$this->table} ORDER BY {$this->column}");
		$optionsArray = DatabaseRow::filterValuesFromRowsForSingleKey($this->column, $optionsRows);
		$this->options = $optionsArray;
	}

	public function get($string) {
		return $this->$string;
	}

	public function getFilterName() {
		return $this->filterName;
	}

	public function getTable() {
		return $this->table;
	}
	
	public function getColumn() {
		return $this->column;
	}
	
	public function getOptions() {
		return $this->options;
	}

	public function setColumnCount($i) {
		$this->columnCount = $i;
	}

	public function createHtmlTable() {
		$html = "
			<table>
				<tr>
					<th colspan='{$this->columnCount}'>
						".$this->filterName."
					</th>
				</tr>
				<tr>					
		";

		for ($i=0; $i < $this->columnCount; $i++) { 		
			$count = count($this->options)/$this->columnCount;
			// Problem: doppelte Einträge, wenn $count einen Rest hat

			$width = 100/$this->columnCount;
			$html .= "<td style='width:".$width."%'><table>";

			
			for ($j=0; $j < $count; $j++) {	
				$value = $this->options[$j + $i * $count];
		
				if($value != '') {
					$html .= "
						<tr>
							<td width='1px'>
								<div class='ui checkbox'>
							    	<input 
										type='checkbox' 
										tabindex='0' 
										name='filter_{$this->table}-{$this->column}[]'
										value='{$value}' 
										id='filter_{$this->table}-{$this->column}'
										class='hidden'
									>
							      	<label>".uppercase(bool_to_lbl($value))."</label>
							    </div>
							</td>
						</tr>
					";				
				}
			}
			$html .= '</td></tr></table>';
		}
		$html .= '</tr></table>';

		return $html;
	}

	function getFilterQuery($array) {
		unset($array['class']);
		$array_string = array_to_string($array);
		// TODO: Prepare!!!!
		$sql = "
			SELECT {$this->table}.contact 
			FROM {$this->table}
			WHERE {$this->table}.{$this->column} IN ({$array_string});
		";
		return $sql;
	}
}