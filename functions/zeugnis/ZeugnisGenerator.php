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
	private $nachname;
	private $vorname;
	private $datum;
	private $anfangsdatum;

	function __construct() {
		$this->member = new MemberClass();
		$nachname = $this->member->getNachname();
		$vorname = $this->member->getVorname();
		$this->datum = date("d.m.Y") ;
		

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
	function setGeburtsdatum($geburtsdatum){
		$this->geburtsdatum = $geburtsdatum;
	}
	function setAnfangsdatum($anfangsdatum){
		$this->Anfangsdatum = $anfangsdatum;
	}
	function getLeistungsbereitschaftBaustein() {
		global $zeit, $gender, $nachname;

		$bereitschaft = [ 
				0 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "jederzeit in überdurchschnittlichem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				1 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "jederzeit in hohem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				2 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in hohem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				3 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in genügendem Maße durch Leistungsbereitschaft, Engagement und Eigeninitiative. ",
				4 => ($zeit == 1 ? 'überzeugt ' : 'überzeugte ') . ($gender == "weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in meist genügendem Maße durch Leistungsbereitschaft und nätiges Engagement. " 
		];
		return $bereitschaft[$this->leistungsbereitschaft];
	}

	function getWeiterbildungBaustein() {
		global $zeit, $gender, $nachname;
		$weiterbildungsarray= [
			0=> "stets sehr erfolgreich und intensiv. \n\n" ,
			1=> "sehr erfolgreich und intensiv. \n\n " ,
			2=> "sehr erfolgreich. \n\n" ,
			3=> "erfolgreich. \n\n" ,
			4=> "mit genügendem Erfolg. \n\n"] ;
		return $weiterbildungsarray[$this->weiterbildung];
	}
	
	function getSozialverhaltenBaustein(){
		global $zeit, $gender, $nachname;

$sozialverhaltenarray= [ 
	0=> ($gender=="weiblich" ? 'Ihr ' : 'Sein ') . "persönliches Verhalten " . ($zeit==1 ? 'ist ' : 'war ') . "jederzeit vorbildlich. In " . ($gender=="weiblich" ? 'ihrem ' : 'seinem ') . "Umgang mit dem Vorstand, den Ressortleitern und den Mitgliedern " . ($zeit==1 ? 'versteht ' : 'verstand ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "es stets, eine vertrauensvolle und offene Atmosphäre zu schaffen. \n\n " ,
	1=> ($gender=="weiblich" ? 'Ihr ' : 'Sein ') . "persänliches Verhalten " . ($zeit==1 ? 'ist ' : 'war ') . "vorbildlich. Im Umgang mit dem Vorstand, den Ressortleitern und den Mitgliedern " . ($zeit==1 ? 'ist ' : 'war ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "geschätzt. \n\n" ,
	2=> "Bei dem Vorstand, den Ressortleitern und den Mitgliedern " . ($zeit==1 ? 'ist ' : 'war ') . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "aufgrund " . ($gender=="weiblich" ? 'ihres ' : 'seines ') . "ausgeglichenen Wesens anerkannt. " . ($gender=="weiblich" ? 'Ihr ' : 'Sein ') . "Verhalten " . ($zeit==1 ? 'ist ' : 'war ') . "einwandfrei. \n\n" ,
	3=> "Beim Vorstand, den Ressortleitern und den Mitgliedern " . ($zeit==1 ? 'ist ' : 'war ') . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in der Regel aufgrund " . ($gender=="weiblich" ? 'ihres ' : 'seines ') . "ausgeglichenen Wesens anerkannt. " . ($gender=="weiblich" ? 'Ihr ' : 'Sein ') . "Verhalten " . ($zeit==1 ? 'ist ' : 'war ') . "ohne Tadel. \n\n" ,
	4=> "Bei den Mitgliedern " . ($zeit==1 ? 'ist ' : 'war ') . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "in der Regel aufgrund " . ($gender=="weiblich" ? 'ihres ' : 'seines ') . "ausgeglichenen Wesens anerkannt. " . ($gender=="weiblich" ? 'Ihr ' : 'Sein ') . "Verhalten gab zu kleiner Klage Anlass. \n\n"] ;

	return $sozialverhaltenarray[$this->sozialverhalten];

	}


	function getZuverlässigkeitBaustein(){
		global $zeit, $gender, $nachname;


	$zuverlässigkeitarray= [ 
		0=> ($zeit==1 ? 'überzeugt ' : 'überzeugte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns insbesondere immer wieder durch " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "absolute Zuverlässigkeit, Offenheit und Geradlinigkeit. " ,
		1=> ($zeit==1 ? 'überzeugt ' : 'überzeugte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns insbesondere immer durch " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "hohe Zuverlässigkeit, Offenheit und Geradlinigkeit. " ,
		2=> ($zeit==1 ? 'überzeugt ' : 'überzeugte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns insbesondere durch " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "Zuverlässigkeit, Offenheit und Geradlinigkeit. " ,
		3=> ($zeit==1 ? 'zeigt ' : 'zeigte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns insbesondere " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "Zuverlässigkeit, Offenheit und Geradlinigkeit. " ,
		4=> ($zeit==1 ? 'zeigt ' : 'zeigte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns im Großen und Ganzen " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "Offenheit und Geradlinigkeit. "] ;

		return $zuverlässigkeitarray[$this->zuverlässigkeit];

	}

	function getArbeitsergebnisBaustein(){
		global $zeit, $gender, $nachname;

		$arbeitsergebnisarray= [
		0=> "Mit den erzielten Arbeitsergebnissen " . ($zeit==1 ? 'stellt ' : 'stellte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns jederzeit sowohl qualitativ als auch quantitativ außerordentlich zufrieden. \n\n" ,
		1=> "Mit den erzielten Arbeitsergebnissen " . ($zeit==1 ? 'stellt ' : 'stellte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns jederzeit sowohl qualitativ als auch quantitativ sehr zufrieden. \n\n" ,
		2=> "Mit den erzielten Arbeitsergebnissen " . ($zeit==1 ? 'stellt ' : 'stellte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns qualitativ und quantitativ voll zufrieden. \n\n" ,
		3=> "Mit den erreichten Arbeitsergebnissen " . ($zeit==1 ? 'stellt ' : 'stellte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns qualitativ und quantitativ zufrieden. \n\n" ,
		4=> "Mit den meist erreichten Arbeitsergebnissen " . ($zeit==1 ? 'stellt ' : 'stellte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "uns qualitativ und quantitativ in der Regel zufrieden. \n\n"] ;

		return $arbeitsergebnisarray[$this->arbeitsergebnis];


	}

	function getArbeitsweiseBaustein(){
		global $zeit, $gender, $nachname;


		$arbeitsweisearray= [
		0 => "Mit äußerst hoher Präzision und Effizienz " . ($zeit==1 ? 'meistert ' : 'meisterte ') . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "stets alle internen und externen Projekte sowie Ressortaufgaben auf einem jederzeit sehr hohen Niveau. " ,
		1 => "Mit sehr hoher Präzision und Effizienz " . ($zeit==1 ? 'meistert ' : 'meisterte ') . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "alle internen und externen Projekte sowie Ressortaufgaben selbstständig auf einem jederzeit hohen Niveau. " ,
		2=> "Die Arbeitsweise von " . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . ($zeit==1 ? 'ist ' : 'war ') . "effizient und zielorientiert. Die anfallenden internen und externen Projekte sowie Ressortaufgaben " . ($zeit==1 ? 'erledigt ' : 'erledigte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "selbständig auf einem zufrieden stellenden Niveau. " ,
		3=> "Die Arbeitsweise von " . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . ($zeit==1 ? 'ist ' : 'war ') . "meist effizient. Die anfallenden internen und externen Projekte sowie Ressortaufgaben " . ($zeit==1 ? 'erledigt ' : 'erledigte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "auf einem ausreichenden Niveau. " ,
		4=> "Die Arbeitsweise von " . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . ($zeit==1 ? 'ist ' : 'war ') . "zuweilen effizient. Die anfallenden internen und externen Projekte sowie Ressortaufgaben " . ($zeit==1 ? 'erledigt ' : 'erledigte ') . ($gender=="weiblich" ? 'sie ' : 'er ') . "im Großen und Ganzen auf einem ausreichendem Niveau. "] ;

		return $arbeitsweisearray[$this->arbeitsweise];
	}

	function getSchlussformel (){
		global $zeit, $gender, $nachname;

		$schlussformel= [
		1=> ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "scheidet aus eigenem Wunsch aus dem Verein aus. Mit großem Bedauern haben wir diese Entscheidung aufgenommen. Wir danken " . ($gender=="weiblich" ? 'ihr ' : 'ihm ') . "für " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "stets sehr guten Leistungen, die " . ($gender=="weiblich" ? 'sie ' : 'er ') . "für Heinrich Heine Consulting erbracht hat und wünschen " . ($gender=="weiblich" ? 'ihr ' : 'ihm ') . "auf " . ($gender=="weiblich" ? 'ihrem ' : 'seinem ') . "weiteren Berufs- und Lebensweg alles Gute, viel Glück und Erfolg. \n\n\n" ,
		2=> "Dieses Zeugnis wurde auf Wunsch von " . ($gender=="weiblich" ? 'Frau ' : 'Herrn ') . "$nachname " . "ausgestellt. Wir danken " . ($gender=="weiblich" ? 'ihr ' : 'ihm ') . "für " . ($gender=="weiblich" ? 'ihre ' : 'seine ') . "stets sehr guten Leistungen, die " . ($gender=="weiblich" ? 'sie ' : 'er ') . "für Heinrich Heine Consulting erbringt und wünschen " . ($gender=="weiblich" ? 'ihr ' : 'ihm ') . "auf " . ($gender=="weiblich" ? 'ihrem ' : 'seinem ') . "weiteren Berufs- und Lebensweg alles Gute, viel Glück und Erfolg. \n\n\n"] ;

		return $schlussformel[$this->zeit];
	}

	
	function setGeschlecht ($gender){
		$this->gender = $gender;	
	}
	
	function setZeit ($zeit) {
		$this->zeit = $zeit;
		
	}

	function generateZeugnis (){
		global $zeit, $gender, $nachname, $vorname, $datum, $anfangsdatum, $geburtsdatum;

		
			$text = "<p> <b> Arbeitszeugnis </b> </p>" ;
			$text .= "<p> Für " . " <b> $vorname  </b>" . " <b> $nachname, </b> " . "<b> geboren am </b>" . " <b>  $geburtsdatum,  </b>" . "<b> Mitglied </b>" . " <b> vom </b> " . " <b> $anfangsdatum</b>" . "<b>. </b> </p> <p></p> "; 
			$text .= "<p> Die als Verein firmierte Heinrich Heine Consulting (HHC) ist seit Juli 2007 die erste studentische Unternehmensberatung Düsseldorfs. Schwerpunkte der Unternehmensberatung sind die Bereiche \"Marketing\", \"Finanzen\", \"Personalberatung\", \"Gründungsmanagement\", \"Vereinsrechtsvorträge\" sowie Innovationsbenchmarkanalysen (IMP?rove). Heinrich Heine Consulting versteht sich als Schnittstelle zwischen Wissenschaft und realwirtschaftlicher Praxis, die den Kontakt zu potenziellen Auftraggebern herstellt und ihre Mitglieder gezielt schult und weiterbildet, um die Qualität der Beratungsprojekte zu gewährleisten. </p>" ;

			$text .= "<p> Im Verein war " . ($gender=="weiblich" ? 'Frau ' : 'Herr ') . "$nachname " . "im Ressort " . "$ressort " . "tätig. </p> <p>  </p> <p>  </p>" ;

			$text .= "<p>" . $this->getArbeitsweiseBaustein() . $this->getArbeitsergebnisBaustein() . "</p>";
			$text .= "<p> Als Mitglied " . $this-> getLeistungsbereitschaftBaustein() . "Zudem " . $this-> getZuverlässigkeitBaustein() . "Die Möglichkeit der Weiterbildung im Verein " . $this-> getWeiterbildungBaustein() . "</p>" ;

			$text .= "<p> {$this->getSozialverhaltenBaustein()} </p> " ;
			$text .= "$schlussformel[$schlussformelIndex]" ;
			
			$text .= "<p> </p> <p> Düsseldorf, " . $this->datum . " </p>" ;

		return $text;

	}
}