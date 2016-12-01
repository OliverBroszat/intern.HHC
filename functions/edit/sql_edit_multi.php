<?php

// wordpress autoloader
require_once(explode('wp-content',__DIR__)[0].'wp-load.php');
 

// $root = get_template_directory();
// require_once("$root/functions/edit/sql_edit.php");


print json_encode($_POST);
