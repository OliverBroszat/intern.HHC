<?php
/**
 * Template Name: KAS Connection
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

function createAccount($prefix, $password) {
	// URIs zu den WSDL-Dateien
	$WSDL_AUTH = 'https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl';
	$WSDL_API = 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl';

	// Logindaten
	$kas_user = 'w01495bb';  // KAS-Logon
	$kas_pass = 'dMwoa9o3odFe';  // KAS-Passwort
	$session_lifetime = 100;  // Gültigkeit des Tokens in Sek. bis zur neuen Authentifizierung
	$session_update_lifetime = 'N'; // bei N läuft die Session nach 1800 Sekunden ab, bei Y verlängert sich die Session mit jeder Benutzung
	$SoapLogon = new SoapClient($WSDL_AUTH);  // url zur wsdl - Datei
	$CredentialToken = $SoapLogon->KasAuth(json_encode(array(
                        'KasUser' => $kas_user,
                        'KasAuthType' => 'sha1',
                        'KasPassword' => sha1($kas_pass),
                        'SessionLifeTime' => $session_lifetime,
                        'SessionUpdateLifeTime' => $session_update_lifetime
                        )));
	// Parameter für die API-Funktion
	$Params = array(
		'mail_password' => $password,
		'local_part' => $prefix,
		'domain_part' => 'hhc-duesseldorf.de',
		'responder' => '1230764400|1259622000',
		'responder_text' => 'Ich bin von [Start] bis [Ende] nicht erreichbar.',
		'copy_address' => null,
		'mail_sender_alias' => null
	);
	// Verbindung herstellen und RPC durchführen
	$SoapRequest = new SoapClient($WSDL_API);
	$req = $SoapRequest->KasApi(json_encode(array(
              'KasUser' => $kas_user,                 // KAS-User
              'KasAuthType' => 'session',             // Auth per Sessiontoken
              'KasAuthData' => $CredentialToken,      // Auth-Token
              'KasRequestType' => 'add_mailaccount',     // API-Funktion
              'KasRequestParams' => $Params           // Parameter an die API-Funktion
              )));
	return $req['Response']['ReturnInfo'];
}

get_header();

?>

<style>
input {
	vertical-align: top;
}

div.response {

}
</style>

<h1>Mailadresse einrichten</h1>

<?php

$status = "<div class='msg' style='background-color: #fff; color: #444648; font-size: 1em;'>
		Hallo, HHC-ler!<br>
		Hier kannst du dir deine neue HHC Adresse einrichten<br><br>

		WICHTIG: Trage unten NUR den Mail-Präfix ein, also alles VOR dem @hhc-duesseldorf. Zum Beispiel alexander.schaefer (mehr nicht!!).<br>
		Bitte trage dich nur ein einziges Mal ein.<br><br>

		Bei Fragen oder Problemen, wende dich bitte an Alexander Schäfer (+49 173 47 35 210) 
	</div>";

if ($_POST['action'] == 'create' && ($_POST['mail_prefix']=='' || $_POST['mail_password'] == '' || $_POST['mail_alterntive'] == '')) {
	$status = "<div class='msg'>Bitte fülle alle Felder aus!</div>";
}

if ($_POST['action'] == 'create') {
	try {
		$username = createAccount($_POST['mail_prefix'], $_POST['mail_password']);
		$loginID = $_POST['mail_prefix'] . '@hhc-duesseldorf.de';

		$message = "Anmeldung erfolgreich. Du kannst dich unter<br><a href='mail.hhc-duesseldorf.de'>mail.hhc-duesseldorf.de</a><br>anmelden.<br>All deine Mails werden von nun an auf dieses Konto gesendet!";

		$password = $_POST['mail_password'];
		$login_data = " Deine Logindaten lauten wie folgt: $loginID $password";


		if(@mail($_POST['mail_alternative'], "HHC-Account", $message . $login_data))
		{
			$status = "<div class='msg' style='background-color: green;'>$message</div>";
		}else{
			$status = "<div class='msg'>Fehler : Die Bestätigungsemail konnte nicht versandt werden.</div>";
		}

	}
	catch (Exception $e) {
		$status = "<div class='msg'>Fehler : ".$e->getMessage()."</div>";
	}
}

echo $status;

?>

<table class='form'>
	<form id='mail_input' action='#' method='POST'>
	<input type='hidden' name='action' value='create' />
	
	<tr>
		<td>
			Mail Präfix (also alles VOR '@hhc-duesseldorf)'
		</td>
	</tr>
	<tr>
		<td>
			<input type='text' name='mail_prefix' placeholder='Vorname.Nachname' value="<?php echo $_POST['mail_prefix']; ?>" style='text-align: center; width: 50%;'/>
		<span style='vertical-align: middle; font-size: 20px;'>
			@hhc-duesseldorf.de
		</span>
		</td>
	</tr>
	<tr>
		<td>
			Dein Passwort (ACHTUNG: wird im Klartext angezeigt!)
		</td>
	</tr>
	<tr>
		<td>
			<input type='text' name='mail_password' placeholder='Dein Passwort' value="<?php echo $_POST['mail_password']; ?>"/>
		</td>
	</tr>
	<tr>
		<td>
			Alternative Emailadresse (dorthin werden deine Daten zur Bestätigung geschickt!)
		</td>
	</tr>
	<tr>
		<td>
			<input type='text' name='mail_alternative' placeholder='andere Mailadresse' value="<?php echo $_POST['mail_alternative']; ?>"/>
		</td>
	</tr>
	<tr>
		<td align='center' style='font-size: 26px;'  >NOCHMAL ALLES ÜBERPRÜFEN!</td>
	</tr>
	<tr>
		<td align='center'  ><button type='submit' style='margin-bottom: 20px;'  >Absenden!</button></td>
	</tr>
	</form>
</table>