<?php

class Date {
	private $start_date;
	private $start_time;
	private $end_date;
	private $end_tiem;
	private $ganztägig;
	private $title;
	private $location;
	private $description;
	private $contacts;
	
	public function __contruct($start_date=null, $start_time=null, $end_date=null, $end_tiem=null, $ganztägig=null, $title=null, $location=null, $description=null, $contacts=null) {
		$this->start_date = $start_date;
		$this->start_time = $start_time;
		$this->end_date = $end_date;
		$this->end_tiem = $end_tiem;
		$this->ganztägig = $ganztägig;
		$this->title = $title;
		$this->location = $location;
		$this->description = $description;
		$this->contacts = $contacts;
	}

	/*** GETTER ***/

    public function getStartDate() {
        return $this->start_date;
    }

    public function getStartTime() {
        return $this->start_time;
    }

    public function getEndDate() {
        return $this->end_date;
    }

    public function getEndTiem() {
        return $this->end_tiem;
    }

    public function getGanztägig() {
        return $this->ganztägig;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getContacts() {
        return $this->contacts;
    }


    /*** SETTER ***/

    private function _setContacts($contacts) {
        $this->contacts = $contacts;
        return $this;
    }

    private function _setStartDate($start_date) {
        $this->start_date = $start_date;
        return $this;
    }

    private function _setStartTime($start_time) {
        $this->start_time = $start_time;
        return $this;
    }

    private function _setEndDate($end_date) {
        $this->end_date = $end_date;
        return $this;
    }

    private function _setEndTiem($end_tiem) {
        $this->end_tiem = $end_tiem;
        return $this;
    }

    private function _setGanztägig($ganztägig) {
        $this->ganztägig = $ganztägig;
        return $this;
    }

    private function _setTitle($title) {
        $this->title = $title;
        return $this;
    }

    private function _setLocation($location) {
        $this->location = $location;
        return $this;
    }

    private function _setDescription($description) {
        $this->description = $description;
        return $this;
    }
}