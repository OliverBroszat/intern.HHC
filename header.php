<?php
/**
 * @package WordPress
 * @subpackage intern-HHC
 * @since intern-HHC
 */

$root_uri = get_template_directory_uri(); 
$root = get_template_directory();

require_once("$root/functions/main_functions.php");

global $user_ID;

get_currentuserinfo();
if(!('' == $user_ID)){
	$backend_button =  "<a href='/wp-admin'><button class='loginout'>Backend</button></a>";
}

$loginout = wp_loginout($_SERVER['REQUEST_URI'], false);
$list_pages = wp_list_pages(array(
	'title_li' => __( '' ),
	'echo' => 0
));

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	
	<script src='<?php echo $root_uri; ?>/import/1.12.0.jquery.min.js'></script>
	<script src='<?php echo $root_uri; ?>/import/1.11.4.jquery-ui.min.js'></script>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/import/1.11.4.jquery-ui.min.css'>

	<link rel='stylesheet' href='<?php echo $root_uri; ?>/style.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/style_suche.css'/>
	<link rel='stylesheet' href='<?php echo $root_uri; ?>/styles/style_home.css'/>
	
	<script src='<?php echo $root_uri; ?>/js/search.js'></script>

	<title><?php echo $title; ?></title>

</head>
<body>
	<div class = 'admin-bar'>
		<?php echo $backend_button; ?>
		<button class='loginout'><?php echo $loginout; ?></button>
	</div>
	<nav class='nav panel full-width'>
		<?php echo $list_pages; ?>
	</nav>
