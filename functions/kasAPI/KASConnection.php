<?php
/**
 * Template Name: KAS Connection
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

function executeAPICommand($command, $params) {
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
	// Verbindung herstellen und RPC durchführen
	$SoapRequest = new SoapClient($WSDL_API);
	$req = $SoapRequest->KasApi(json_encode(array(
              'KasUser' => $kas_user,                 // KAS-User
              'KasAuthType' => 'session',             // Auth per Sessiontoken
              'KasAuthData' => $CredentialToken,      // Auth-Token
              'KasRequestType' => $command,   		  // API-Funktion
              'KasRequestParams' => $params           // Parameter an die API-Funktion
              )));
	return $req;
}

?>