<?php
class ZeugnisGenerator {
	private $leistungsbereitschaft;
	private $weiterbildung;
	private $arbeitsweise;
	private $arbeitsergebnis;
	private $sozialverhalten;
	private $zuverlässigkeit;
	private $zeugnisText;
	function __construct($leistungsbereitschaft, $weiterbildung, $arbeitsweise, $arbeitsergebnis, $sozialverhalten, $zuverlässigkeit) {
		$this->leistungsbereitschaft = $leistungsbereitschaft;
		$this->weiterbildung = $weiterbildung;
		$this->arbeitsweise = $arbeitsweise;
		$this->arbeitsergebnis = $arbeitsergebnis;
		$this->sozialverhalten = $sozialverhalten;
		$this->zuverlässigkeit = $zuverlässigkeit;
	}
}