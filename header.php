<?php
/**
 * @package WordPress
 * @subpackage intern-HHC
 * @since intern-HHC
 */

$root_uri = get_template_directory_uri(); 

global $user_ID;

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	
	<script src='<?php echo $root_uri; ?>/import/1.12.0.jquery.min.js'></script>
	<script src='<?php echo $root_uri; ?>/import/1.11.4.jquery-ui.min.js'></script>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/import/1.11.4.jquery-ui.min.css'>
	
	<!-- <script src='<?php echo $root_uri; ?>/import/jquery.validate.min.js'></script> -->
	<!-- <script src='<?php echo $root_uri; ?>/import/jquery.validate_messages_de.js'></script> -->

	<script src='<?php echo $root_uri; ?>/js/functions.js'></script>
	<script src='<?php echo $root_uri; ?>/js/popup.js'></script>

	<link rel='stylesheet' href='<?php echo $root_uri; ?>/style.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/style_suche.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/style_home.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/style_edit.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/expandablecontent.css'/>

	<title><?php echo $title; ?></title>

</head>
<body>
	<div class = 'admin-bar'>
		<?php 
			// Login-Button and Backend-Button
			$loginout = "<a href='" . wp_login_url( get_permalink() ) . "'><button class='loginout'>Anmelden</button></a>";

			get_currentuserinfo();
			if(!('' == $user_ID)){
				$backend_button =  "<a href='" . admin_url() . "'><button class='loginout'>Backend</button></a>";
				$loginout = "<a href='" . wp_logout_url( get_permalink() ) . "'><button class='loginout'>Abmelden</button></a>";
			}

			echo $backend_button;
			echo $loginout;
		?>
	</div>
	<nav class='nav panel full-width'>
		<?php 
			$list_pages = wp_list_pages(array(
				'title_li' => __( '' ),
				'echo' => 0
			));

			echo $list_pages; 
		?>
	</nav>
