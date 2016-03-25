<?php
/**
 * Template Name: SQL Register
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */
?>

<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
	<title>SQL Register</title>
</head>

<?php 

if(
	$_POST['vorname'] != '' &&
	$_POST['nachname'] != '' &&
	$_POST['mail_privat'] != '' &&
	$_POST['mail_hhc'] != '' &&
	$_POST['phone1'] != '' &&
	$_POST['street'] != '' &&
	$_POST['number'] != '' &&
	$_POST['postal'] != '' &&
	$_POST['city'] != '' &&
	$_POST['birth_date'] != '' &&
	$_POST['active'] != '' &&
	$_POST['ressort'] != '' &&
	$_POST['position'] != '' &&
	$_POST['start'] != '' &&
	$_POST['course1'] != '' &&
	$_POST['status1'] != '' &&
	$_POST['start1'] != ''
){
	$status = 'ok';
	if($_POST['uni1'] == 'andere' && $_POST['school1'] == '') {
		$status = 'invalid';
	}
	if($_POST['uni2'] == 'andere' && $_POST['school2'] == '') {
		$status = 'invalid';
	}
	

	global $wpdb;

	// Contact
	$wpdb->insert( 
		'Contact', 
		array(
			'first_name' => $_POST['vorname'],
			'last_name' => $_POST['nachname'],
			'birth_date' => $_POST['birth_date']
		)
	);
	$Contactid = $wpdb->insert_id;
	//echo "KontaktID:  ".$Contactid;


	// Image
	/*if ( ! function_exists( 'wp_handle_upload' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}

	$uploadedfile = $_FILES['image'];
	$movefile = wp_handle_upload( $uploadedfile);
	
	$wpdb->insert( 'Image', array('path' => 'value1'));
	$imageid = $wpdb->insert_id;*/

	if (isset( $_POST['my_image_upload_nonce']) && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' )) {
			// The nonce was valid and the user has the capabilities, it is safe to continue.

			// These files need to be included as dependencies when on the front end.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			
			// Let WordPress handle the upload.
			// Remember, 'my_image_upload' is the name of our file input in our form above.
			$attachment_id = media_handle_upload( 'my_image_upload',0);
			
			if ( is_wp_error( $attachment_id ) ) {
				// There was an error uploading the image.
			} else {
				// The image was uploaded successfully!
				$wpdb->insert(
					'Image',
					array(
						'id' => $attachment_id,
						'contact_id' => $Contactid
					)
				);
			}
	} else {
		// The security check failed, maybe show the user an error.
	}

	// Adress
	$wpdb->insert( 
		'Address', 
		array(
			'street' => $_POST['street'],
			'number' => $_POST['number'], 
			'addr_extra' => $_POST['addr_extra'],
			'postal' => $_POST['postal'],
			'city' => $_POST['city'],
			'contact' => $Contactid, 
			'description'=>" "
		)
	);
	$Addressid = $wpdb->insert_id;

	// Ressort
	$ressort = $_POST['ressort'];
	$ressortidtmp = $wpdb->get_results("SELECT id FROM Ressort WHERE name='$ressort'");

	$ressortid = $ressortidtmp[0]->id;

	// Member
	$wpdb->insert( 
		'Member', 
		array(
			'contact'=> $Contactid, 
			'ressort'=>$ressortid, 
			'active'=>$_POST['active'], 
			'position' => $_POST['position'], 
			'joined'=>$_POST['start'], 
			'left' => $_POST['end']
		)
	);
	$Memberid = $wpdb->insert_id;

	// Mail Privat
	$wpdb->insert( 'Mail', array('address'=>$_POST['mail_privat'], 'contact' => $Contactid));
	$Mail1id = $wpdb->insert_id;

	// Mail HHC
	$wpdb->insert( 'Mail', array('address'=>$_POST['mail_hhc'], 'contact' => $Contactid));
	$Mail2id = $wpdb->insert_id;

	// Phone
	$wpdb->insert( 'Phone', array('number'=>$_POST['phone1'], 
	'contact' => $Contactid));
	$Phone_mobileid = $wpdb->insert_id;

	// Phone 2
	if($_POST['phone2'] != ''){
		$wpdb->insert( 
			'Phone', 
				array('number'=>$_POST['phone2'], 
					'contact' => $Contactid));
					 $phone_id = $wpdb->insert_id;
	};
	
	
	// Studium
	if($_POST['uni1'] == "andere"){
		$school = $_POST['school1'];
	}else{
		$school = $_POST['uni1'];
	}

	$wpdb->insert( 
		'Study', 
		array(
			'contact'=>$Contactid, 
			'status'=>$_POST['status1'], 
			'school'=>$school, 
			'course'=>$_POST['course1'], 
			'start'=>$_POST['start1'], 
			'end'=>$_POST['end1'], 
			'focus'=>$_POST['focus1'], 
			'info_extra'=>$_POST['extra1']
		)
	);
	$Study1id = $wpdb->insert_id;

	// Studium 2
	if (isset($_POST['add_studium2'])) {
		if($_POST['uni2'] == "andere"){
			$school = $_POST['school2'];
		}else{
			$school = $_POST['uni2'];
		}
		if($school != '') {
			$wpdb->insert( 
				'Study', 
				array(
					'contact'=>$Contactid, 
					'status'=>$_POST['status2'], 
					'school'=>$school, 
					'course'=>$_POST['course2'], 
					'start'=>$_POST['start2'], 
					'end'=>$_POST['end2'], 
					'focus'=>$_POST['focus2'], 
					'info_extra'=>$_POST['extra2']
				)
			);
			$Study2id = $wpdb->insert_id;
		}
	}
}
else {
	$status = 'invalid';
}

if($status == 'ok') {
	echo"<script> window.location='http://neu.hhc-duesseldorf.de/wp-content/themes/twentyfourteen-child/output.php?status=ok'</script>";
}
else {
	if($status == 'invalid') {
		echo"<script> window.location='http://neu.hhc-duesseldorf.de/wp-content/themes/twentyfourteen-child/output.php?status=failed'</script>";
	}
}

?>