<?php
/**
 * Template Name: Template Marek
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

get_header(); 

//$array = $wpdb->get_results('SELECT * FROM TEST_USERS;');
var_dump($_POST);
echo "<br>";
echo "<br>";
echo $_POST['street'];

$wpdb->insert( 'Image', array('path' => 'value1'));
$imageid = $wpdb->insert_id;

$wpdb->insert( 'Contact', array('first_name' => $_POST['vorname'],
'last_name' => $_POST['nachname'], 'image' => $imageid));
$Contactid = $wpdb->insert_id;
echo "KontaktID:  ".$Contactid;

$wpdb->insert( 'Address', array('street' => $_POST['street'],
'number' => $_POST['number'], 'addr_extra' => $_POST['addr_extra'],
'postal'=> $_POST['postal'], 'contact' => $Contactid, 'description'=>" "));
$Addressid = $wpdb->insert_id;

$ressort = $_POST['ressort'];
$ressortidtmp = $wpdb->get_results("SELECT id FROM Ressort WHERE name='$ressort'");

echo "<br>";
echo "<br>";
$ressortid = $ressortidtmp[0]->id;
echo $ressortid;
echo "<br>";
echo "<br>";


$wpdb->insert( 'Member', array('contact'=> $Contactid, 'ressort'=>$ressortid, 
'active'=>$_POST['active'], 'position' => $_POST['position'], 
'joined'=>$_POST['start'], 'left' => $_POST['end']));
$Memberid = $wpdb->insert_id;

$wpdb->insert( 'Mail', array('address'=>$_POST['mail_privat'], 
'contact' => $Contactid));
$Mail1id = $wpdb->insert_id;

$wpdb->insert( 'Mail', array('address'=>$_POST['mail_hhc'], 
'contact' => $Contactid));
$Mail2id = $wpdb->insert_id;

$wpdb->insert( 'Phone', array('number'=>$_POST['phone_mobile'], 
'contact' => $Contactid));
$Phone_mobileid = $wpdb->insert_id;

$wpdb->insert( 'Phone', array('number'=>$_POST['phone'], 
'contact' => $Contactid));
$Phoneid = $wpdb->insert_id;

$wpdb->insert( 'Study', array('contact'=>$Contactid, 
'status'=>$_POST['status1'], 'school'=>$_POST['school1'], 
'course'=>$_POST['course1'], 'start'=>$_POST['start1'], 
'end'=>$_POST['end1'], 'focus'=>$_POST['focus1'], 'info_extra'=>$_POST['extra1']));
$Study1id = $wpdb->insert_id;

$wpdb->insert( 'Study', array('contact'=>$Contactid, 
'status'=>$_POST['status2'], 'school'=>$_POST['school2'], 
'course'=>$_POST['course2'], 'start'=>$_POST['start2'], 
'end'=>$_POST['end2'], 'focus'=>$_POST['focus2'], 'info_extra'=>$_POST['extra2']));
$Study2id = $wpdb->insert_id;

echo "<br>";
echo "<br>";
echo $lastid;
?>




	

<?php
get_sidebar();
get_footer();
