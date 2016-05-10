<?php
/**
 * Template Name: Liste
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */


//******* functions *******

// change the date from YYYY-MM-DD to DD.MM.YYYY
if (!function_exists('change_date_format')) {
	function change_date_format($origDate){
		if($origDate == "0000-00-00"){
			return "-";
		}elseif ($origDate == "") {
			return "";
		}else{
			$newDate = date("d.m.Y", strtotime($origDate));
			return $newDate;
		}
	}
}

// Server:
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

// localhost:
// $root = realpath($_SERVER["DOCUMENT_ROOT"])."/wordpress";


require_once("$root/wp-content/themes/twentyfourteen-child/functions/main_functions.php");

echo html_header('Liste');

?>

<html>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
	<title>Liste</title>
</head>
<body>
	<main>
		<h1>Liste</h1>
		<table class='liste'>
			<tr>
				<th>Nr.</th>
<?php
	// Define Columns
	$cols = array("ID", "Vorname", "Nachname", "Geburtstag", "Bild", "Mail 1 (privat)", "Mail 2 (HHC)", "Phone 1 (Handy)", "Phone 2", "Straße", "Nummer", "Zusatz", "PLZ", "HHC Status", "HHC Position", "HHC Beitritt", "HHC Austritt", "HHC Ressort", "Studium Status", "Hochschule", "Studiengang", "Beginn", "Ende", "Schwerpunkt", "Abschluss", "Studium Status (2)", "Schule (2)", "Studiengang (2)", "Beginn (2)", "Ende (2)", "Schwerpunkt (2)", "Abschluss (2)");

	// print Table Header
	foreach ($cols as $value) {
		echo "<th>$value</th>";
	}
?>
		</tr>
<?php	
	$contact = $wpdb->get_results('SELECT * FROM Contact');

	$liste = array();
	$i = 0;

	// $liste mit Daten aus den Tabellen füllen
	foreach ($contact as $value) {
		$contact_id = $value->id;
		
		// Datenbankabfragen
		$image = $wpdb->get_row("SELECT * FROM Image WHERE id = $value->image");
		$mail = $wpdb->get_results("SELECT * FROM Mail WHERE contact = $contact_id");
		$phone = $wpdb->get_results("SELECT * FROM Phone WHERE contact = $contact_id");
		$address = $wpdb->get_row("SELECT * FROM Address WHERE contact = $contact_id");
		$member = $wpdb->get_row("SELECT * FROM Member WHERE contact = $contact_id");
		$ressort = $wpdb->get_row("SELECT * FROM Ressort WHERE id = $member->ressort");
		$study = $wpdb->get_results("SELECT * FROM Study WHERE contact = $contact_id");
		$attachment_id = $wpdb->get_results("SELECT * FROM Image WHERE contact_id = $contact_id")[0]->id;
		
		// Profilbild
		if($attachment_id != ""){
			$imgsrc = wp_get_attachment_image_src($attachment_id, $size='')[0];
			$imgsrc_thumb = wp_get_attachment_image_src($attachment_id, $size='thumbnail')[0];
			$image = "<a href='$imgsrc' target='_blank'><img src='$imgsrc_thumb' class='profile-picture' alt='Profilbild' /></a>";
		}else{
			$image = "<img class='profile-picture'>";
		};
		
		// Array $liste mit den Daten füllen
		$liste[$i]['nr'] = $i + 1;
		$liste[$i]['id'] = $value->id; 
		$liste[$i]['first_name'] = $value->first_name;
		$liste[$i]['last_name'] = $value->last_name;
		$liste[$i]['birth_date'] = change_date_format($value->birth_date);

		$liste[$i]['image'] = $image;
		
		$liste[$i]['mail1'] = $mail[0]->address;
		$liste[$i]['mail2'] = $mail[1]->address;
		
		$liste[$i]['phone1'] = $phone[0]->number;
		$liste[$i]['phone2'] = $phone[1]->number;
		
		$liste[$i]['street'] = $address->street;
		$liste[$i]['number'] = $address->number;
		$liste[$i]['addr_extra'] = $address->addr_extra;
		$liste[$i]['postal'] = $address->postal;

		if($member->active == 0){
			$liste[$i]['status'] = "Aktiv";
		}
		elseif ($member->active == 1) {
			$liste[$i]['status'] = "Passiv";
		};

		$liste[$i]['position'] = $member->position;
		$liste[$i]['joined'] = change_date_format($member->joined);	
		$liste[$i]['left'] = change_date_format($member->left);
		$liste[$i]['ressort'] = $ressort->name;
		
		$liste[$i]['status1'] = $study[0]->status;
		$liste[$i]['school1'] = $study[0]->school;
		$liste[$i]['course1'] = $study[0]->course;
		$liste[$i]['start1'] = change_date_format($study[0]->start);
		$liste[$i]['end1'] = change_date_format($study[0]->end);
		$liste[$i]['focus1'] = $study[0]->focus;
		$liste[$i]['info_extra1'] = $study[0]->info_extra;

		$liste[$i]['status2'] = $study[1]->status;
		$liste[$i]['school2'] = $study[1]->school;
		$liste[$i]['course2'] = $study[1]->course;
		$liste[$i]['start2'] = change_date_format($study[1]->start);
		$liste[$i]['end2'] = change_date_format($study[1]->end);
		$liste[$i]['focus2'] = $study[1]->focus;
		$liste[$i]['info_extra2'] = $study[1]->info_extra;

		$i ++;
	}
	
	// print $liste		
	foreach ($liste as $row) {
		echo "<tr>";
		foreach ($row as $col) {
			echo "<td>$col</td>";
		}
		echo "</tr>";
	};		
?> 
		</table>
	</main>
</body>
</html>

