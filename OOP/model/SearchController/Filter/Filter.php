<?php

/*
 * Because we're outside the wordpress template directory, no wordpress functionality will be
 * pre-includes for us :(
 * In this block we include all necessary functions and an autoloader by loading the 'wp-load.php'.
 * NOTE: on local host this file has a different path!
 */
if (!function_exists('serverIsRunningOnLocalHost')) {
    function serverIsRunningOnLocalHost() {
        $localHostAddresses = array('127.0.0.1', '::1');
        $currentServerIPAddress = $_SERVER['REMOTE_ADDR'];
        if(in_array($currentServerIPAddress, $localHostAddresses)){
            return true;
        }
        return false;
    }
}

if (!function_exists('loadWordpressFunctions')) {
    function loadWordpressFunctions() {
        $serverRootPath = realpath($_SERVER["DOCUMENT_ROOT"]);
        if (serverIsRunningOnLocalHost()) {
            $serverRootPath = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress';
        }
        require_once("$serverRootPath/wp-load.php");
    }
}

loadWordpressFunctions();


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
		$query = "SELECT DISTINCT $this->column FROM $this->table ORDER BY $this->column";

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

		echo $html;
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