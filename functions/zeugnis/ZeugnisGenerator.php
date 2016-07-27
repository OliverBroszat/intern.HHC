<?php
class ZeugnisGenerator {
	private $leistungsbereitschaft;
	private $weiterbildung;
	private $arbeitsweise;
	private $arbeitsergebnis;
	private $sozialverhalten;
	private $zuverlässigkeit;
	private $gender;
	private $zeit;
	private $member;

	function __construct() {
		$this->member = new MemberClass();
	}
	function setLeistungsbereitschaft($leistungsbereitschaft) {
		$this->leistungsbereitschaft = $leistungsbereitschaft;
	}
	function setWeiterbildung($weiterbildung) {
		$this->weiterbildung = $weiterbildung;
	}
	function setArbeitsweise($arbeitsweise) {
		$this->arbeitsweise = $arbeitsweise;
	}
	function setArbeitsergebnis($arbeitsergebnis) {
		$this->arbeitsergebnis = $arbeitsergebnis;
	}
	function setSozialverhalten($sozialverhalten) {
		$this->sozialverhalten = $sozialverhalten;
	}
	function setZuverlässigkeit($zuverlässigkeit) {
		$this->zuverlässigkeit = $zuverlässigkeit;
	}
	function getLeistungsbereitschaftBaustein() {
		$nachname = $this->member->getNachname();
		$bereitschaft = [ 
				1 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "jederzeit in überdurchschnittlichem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				2 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "jederzeit in hohem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				3 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in hohem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				4 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in genügendem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				5 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in meist genügendem Maße durch Leistungsbereitschaft und nätiges Engagement. " 
		];
		return $bereitschaft[$this->leistungsbereitschaft];
		
	}
	function setGeschlecht ($gender){
		$this->gender = $gender;	
	}
	
	function setZeit ($zeit) {
		$this->zeit = $zeit;
		
	}
}