<?php

class DateController {
	private $base;
	
	public function __contruct() {
		$this->base = new BaseDataController();
	}

	public function getDateById($id) {
		return $base->getSingleRowByQuery("SELECT * FROM date WHERE id=$id;");
	}

	public function getDatesById($ids) {
		$dates = [];
		foreach($ids as $id) {
			array_push($dates, getDateById($id));
		}
		return $dates;
	}

	public function newDate($start_date, $start_time, $end_date, $end_tiem, $ganztägig, $title, $location, $description, $contacts) {
		return new Date($start_date, $start_time, $end_date, $end_tiem, $ganztägig, $title, $location, $description, $contacts);
	}

	public function insertNewDate($date) {
		return $this->insertSingleRowInTable($date);
	}

	public function updateDate($id, $date) {

	}

	public function deleteDate($id) {

	}

	public function editDate($id) {
		$date = $this->getDateById($id);
		$mustache = new MustacheEngine();
		$data = $date->toArray();
		$mustache->render('editDate', $data);
	}

}

class Operations {
	private $dateController;
	private $post;
	function __construct($post) {
		$this->dateController = new DateController();
		$this->post = $post;

		switch ($this->post['op']) {
			case 'new':
				$date = $this->newDateByPost($post);
				$this->insertNewDate($date);
				break;

			case 'delete':
				$this->dateController->deleteDate($this->post['id']);
				break;

			case 'update':
				$date = $this->newDateByPost($post);
				$this->dateController->updateDate($this->post['id'], $date);
				break;
		}
	}

	private function newDateByPost($post) {
		return $this->dateController->newDate(
			$this->post['start_date'], 
			$this->post['start_time'], 
			$this->post['end_date'], 
			$this->post['end_tiem'], 
			$this->post['ganztägig'], 
			$this->post['title'], 
			$this->post['location'], 
			$this->post['description'], 
			$this->post['contacts']
		);
	}
}

if(isset($_POST['op'])) {
	new Operations($_POST);
}