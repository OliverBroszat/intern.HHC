<?php

$root = get_template_directory();
require_once("$root/functions/kasAPI/KASConnection.php");

function createMailingList($name, $targets) {
	$p_targets = array();
	$i = 0;
	foreach ($targets as $mail) {
		$p_targets["target_$i"] = $mail;
		$i++;
	}
	$params = array(
		'local_part' => $name,
		'domain_part' => 'hhc-duesseldorf.de'
	);
	$params = array_merge($params, $p_targets);
	return executeAPICommand('add_mailforward', $params);
}

function getTargetsFromMailingList($name) {
	// Name muss eindeutig sein
	return executeAPICommand('get_mailforwards', array('mail_forward' => $name));
}

function insertAddrIntoList($address, $list) {
	//
	
}


?>