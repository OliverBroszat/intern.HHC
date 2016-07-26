<?php
/**
 * Template Name: Liste
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

//Falls der Besucher nicht eingeloggt ist, wird er auf die Startseite zurückgeleitet
if(!is_user_logged_in() )
{
	wp_redirect( home_url( '' ) );
	exit();
}

?>
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
		$liste[$i]['info_extra1'] = $study[0]->degree;

		$liste[$i]['status2'] = $study[1]->status;
		$liste[$i]['school2'] = $study[1]->school;
		$liste[$i]['course2'] = $study[1]->course;
		$liste[$i]['start2'] = change_date_format($study[1]->start);
		$liste[$i]['end2'] = change_date_format($study[1]->end);
		$liste[$i]['focus2'] = $study[1]->focus;
		$liste[$i]['info_extra2'] = $study[1]->degree;

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


<?php get_footer(); ?>

