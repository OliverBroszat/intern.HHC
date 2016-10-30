<?php 

class SearchController {
	
	private $searchData;	
	private $base;

	public function __construct($post) {
		// $post = array(
		// 		'search' => $_POST['search_text'], 		// e.g. 'alex schäfer düsseldorf'
		// 		'filter' => $filter,					// e.g. array('', '')
		// 		'sort' => $_POST['sort'],				// e.g. 'contact.birth_date'
		// 		'order' => $_POST['order']				// e.g. 'DESC'
		// 	);
		$this->searchData = new SearchData($post);

		$this->base = new BaseDataController();
	}

	public function getSearchData(){
		return $this->searchData;
	}

	public function search() {
		// get queries for filter
		// Durchsuche alle Filter aus Post. Für die Filter, die nicht leer sind, rufe die Funktion getFilterQuery aus der entsprechenden Klasse zu dem Filter auf.
		$queries = array();
		$filterLists = new FilterLists();
		foreach ($this->searchData->getFilter() as $name => $values) {
			if (sizeof($values) > 1) {
				array_push($queries, $filterLists->getSearchFilter()[$values['class']]->getFilterQuery($values));
			}		
		}

		// get IDs for filter
		// store IDs as key (faster?)
		$id_list = array();
		foreach ($queries as $query) {
			$rows = $this->base->selectMultipleRowsByQuery($query);
			// extract IDs from result
			$ids = array();
			foreach ($rows as $row) {
				$id = $row->getValueForKey('contact'); // ForeignKey
				$ids[$id] = true; // store ID as key
			}
			array_push($id_list, $ids);
		}

		// get query for search words
		$searchWordsQuery = $this->searchWordsQuery();

		//get IDs for search words
		$searchWordsIDs = $this->base->selectMultipleRowsByQuery($searchWordsQuery);
		// extract IDs from result
		$ids = array();
		foreach ($searchWordsIDs as $row) {
			$id = $row->getValueForKey('id'); // PrimaryKey
			$ids[$id] = true; // store ID as key
		}

		// combine IDs of filters and search words
		array_push($id_list, $ids);

		// make intersection of all IDs
		if (sizeof($id_list)>1) {
			$id_list = call_user_func_array('array_intersect_assoc', $id_list);
		}else{
			$id_list = $id_list[0];
		}

		// convert IDs from keys to values
		$ids = array();
		foreach ($id_list as $key => $value) {
			array_push($ids, $key);
		}

		// get memberProfiles for IDs
		$member = new MemberDataController(null, new ContactDataController(null, $this->base));
		$memberProfiles = $member->getMultipleMemberProfilesByContactID($ids);

		// return memberProfiles
		return $memberProfiles;
	}


	private function searchWordsQuery() {
		// where must be searched for the search words:
		$searchRange = array( 
		    'Contact' => array(
		    	'id',
		    	'first_name', 
		    	'last_name'
		    ),
		    'Address' => array(
		    	'city',
		    	'postal'
		    ),
		    'Phone' => array(
		    	'number'
		    ),
		    'Study' => array(
		    	'course'
		    )
		);

		// create sql query to search for search words
		$sql = '
			SELECT Contact.id 
			FROM Contact
			LEFT JOIN address ON contact.id = address.contact 
			LEFT JOIN phone ON contact.id = phone.contact 
			LEFT JOIN study ON contact.id = study.contact 
			INNER JOIN member ON contact.id = member.contact 
			INNER JOIN ressort ON member.ressort = ressort.id 
			WHERE 
		';

		if (!empty($this->searchData->getSearchWords()[0])) {
			foreach ($this->searchData->getSearchWords() as $key => $searchWord) {
				$sql .= '( ';
				foreach ($searchRange as $table => $columns) {
					foreach ($columns as $column) {
						$sql .= "{$table}.{$column} LIKE '%{$searchWord}%' OR ";
					}
				}
				$sql = rtrim($sql, ' OR ');
				$sql .= ' ) AND ';
			}
			$sql = rtrim($sql, ' AND ');
		}
		else {
			$sql .= 'true';
		}

		$sql .= " ORDER BY ".$this->searchData->getSort()." ".$this->searchData->getOrder();

		return $sql;
	}

}