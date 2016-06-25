<?php
<<<<<<< HEAD
	var_dump($_POST);
	/*
	ALTERNATIVE: recaptcha lib Beispiel von Google auf Github nutzen
					hat bei mir privat aber nicht funktioniert :(
	if (isset($_POST['g-recaptcha-response'])) {
		$captcha = $_POST['g-recaptcha-response'];
		$privatekey = "6LfSgyMTAAAAAFVCb7v9xwhyOFHZDLRi-q__XwPt";
		$url = 'https://www.google.com/recaptcha/api/siteverify'; // Data vorbereiten
		$data = array(
			'secret' => $privatekey,
			'response' => $captcha,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		); // CURL vorbereiten
		$curlConfig = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $data
		); // CURL'n
		$ch = curl_init();
		curl_setopt_array($ch, $curlConfig);
		$response = curl_exec($ch);
		curl_close($ch);
	}
	$jsonResponse = json_decode($response);

	if ($jsonResponse->success == "true") {
		Prüft ob Captcha erfolgreich abgefragt
		Hier Verarbeitung der Bewerbung einfügen
	}
	 */
=======
	var_dump($_FILES);
?>
<br><br>
<?php
	var_dump($_FILES);
>>>>>>> Oliver_Zeugnis
?>