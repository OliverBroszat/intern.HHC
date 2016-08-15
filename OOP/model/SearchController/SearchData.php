<?php 

class SearchData {

	private $searchWords;
	private $filter;
	private $sort;
	private $order;

	public function __construct($post) {
		$this->searchWords = $this->splitSearchText($post['search_text']);
		$this->filter = $this->makeFilterArrayFromPost($post);
		$this->sort = $post['sort'];
		$this->order = $post['order'];
	}

	public function splitSearchText($searchText) {
		return preg_split("/[\s,]+/", trim($searchText));	
	}

	private function makeFilterArrayFromPost($post){
		$filterLists = new FilterLists();
		$searchFilters = $filterLists->getSearchFilter();
		$filter = array();
		foreach ($searchFilters as $sfKey => $value) {
			$key = "{$value->getTable()}.{$value->getColumn()}";
			$name = "filter_{$value->getTable()}-{$value->getColumn()}";
			$filter[$key] = $post[$name];
			$filter[$key]['class'] = $sfKey;
		}
		return $filter;
	}

    /**
     * Gets the value of searchWords.
     *
     * @return mixed
     */
    public function getSearchWords()
    {
        return $this->searchWords;
    }

    /**
     * Gets the value of filter.
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Gets the value of sort.
     *
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }
}
