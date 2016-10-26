<?php 

class SearchController {
	
	private $searchData;	
	private $base;

	public function __construct($post) {
		$this->searchData = new SearchData($post);
		$this->base = new BaseDataController();
	}

	public function getSearchData(){
		return $this->searchData;
	}

	public function search() {
		// Durchsuche alle Filter aus Post. Für die Filter, die nicht leer sind, rufe die Funktion getFilterQuery aus der entsprechenden Klasse zu dem Filter auf.
		$queries = array();
		$filterLists = new FilterLists();
		foreach ($this->searchData->getFilter() as $name => $values) {
			if (sizeof($values) > 1) {
				array_push($queries, $filterLists->getSearchFilter()[$values['class']]->getFilterQuery($values));
			}		
		}

		$id_list = array();
		foreach ($queries as $query) {
			$rows = $this->base->selectMultipleRowsByQuery($query);
			$ids = array();
			foreach ($rows as $row) {
				$id = $row->getValueForKey('contact');
				$ids[$id] = true;
			}
			array_push($id_list, $ids);
		}
		// echo "<h3>Filter id_list: </h3>";
		// var_dump($id_list);


		$searchWordsQuery = $this->searchWordsQuery();

		// echo "<h3>SearchWordsQuery: </h3> $searchWordsQuery";

		// echo "<hr>";
		
		$searchWordsIDs = $this->base->selectMultipleRowsByQuery($searchWordsQuery);
		$ids = array();
		foreach ($searchWordsIDs as $row) {
			$id = $row->getValueForKey('id');
			$ids[$id] = true;
		}
		// echo "<h3>SearchWords ids: </h3>";
		// var_dump($ids);


		array_push($id_list, $ids);
		// echo "<h3>Filter + SearchWords ids: </h3>";
		// var_dump($id_list);

		if (sizeof($id_list)>1) {
			$id_list = call_user_func_array('array_intersect_assoc', $id_list);
		}else{
			$id_list = $id_list[0];
		}

		// echo "<h3>Intersected IDs: </h3>";
		$ids = array();
		foreach ($id_list as $key => $value) {
			array_push($ids, $key);
		}
		// var_dump($ids);

		// echo "<h3>MemberProfiles: </h3>";

		$contact = new ContactDataController(null, $this->base);
		$member = new MemberDataController(null, $contact);

		$memberProfiles = $member->getMultipleMemberProfilesByContactID($ids);

		return $memberProfiles;
	}



	// private function whereFilter() {
	// 	echo "***************";
	// 	print_r($this->searchData->getFilter());
	// 	echo "***************";
	// 	if (!empty($this->searchData->getFilter())) {
	// 		$sql = '';
	// 		foreach ($this->searchData->getFilter() as $key => $array) {
	// 			foreach ($array as $value) {
	// 				$sql .= "{$key} = '{$value}' OR ";
	// 			}
	// 		}
	// 		return rtrim($sql, ' OR ');
	// 	}
	// 	else {
	// 		return 'true';
	// 	}
	// }

	private function searchWordsQuery() {
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




// // Filter
// $input = array(
// 		'search' => $_POST['search_text'],
// 		'filter' => $filter,
// 		'sort' => $_POST['sort'],
// 		'order' => $_POST['order']
// 	);


// // Für den SELECT Operator
// $search_select = array(
// 	'Contact' => array(
// 		'id',
// 		'prefix',
// 		'first_name',
// 		'last_name',
// 		'birth_date',
// 		'comment',
// 		'skype_name'
// 	),
// 	'Ressort' => array(
// 		'name'
// 	),
// 	'Member' => array(
// 		'active',
// 		'position',
// 		'joined',
// 		'left'
// 	)
// );

// // Für den LIKE Operator
// $search_range = array( 
//   'Contact' => array( 
//     'id',
//     'first_name', 
//     'last_name' 
//   ), 
//   'Address' => array( 
//     'city', 
//     'postal' 
//   ), 
//   'Phone' => array( 
//     'number' 
//   ), 
//   'Study' => array( 
//     'course' 
//   ) 
// ); 
}