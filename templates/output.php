<?php
/**
 * Template Name: Output
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();


$status = $_GET['status'];

if ($status == 'ok'){
	echo "<div class='msg ok'>Vielen Dank!<br>Deine Daten wurden übernommen!</div>";
}
else{
	echo "<div class='msg'>Leider Hat die Dateneingabe nicht geklappt. Hast du alle Pflichtfelder ausgefüllt?<br>Bitte gehe zurück und versuche es noch einmal</div>";
}
?>