<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='/wp-content/themes/twentyfourteen-child/style.css'/>
	<title>Output</title>
</head>

<?php
	$status = $_GET['status'];

	if ($status == 'ok'){
		echo "<div class='msg ok'>Vielen Dank!<br>Deine Daten wurden übernommen!</div>";
	}
	else{
		echo "<div class='msg'>Leider Hat die Dateneingabe nicht geklappt. Hast du alle Pflichtfelder ausgefüllt?<br>Bitte gehe zurück und versuche es noch einmal</div>";
	}
?>