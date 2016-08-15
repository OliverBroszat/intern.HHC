<?php
/**
 * Template Name: Unit Test
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

// Include phpUnit
$root = get_template_directory();

?>

<div class="outer">
	<h1 style="text-transform: none;">intern.HHC</h1>
	<div class="panel">
<?php

echo '<h1>Unit Test Overview</h1>';

/*
Parse /test directory and create table for each test file.
When clicking 'run', the test will be done and the results are passed to this
site via AJAX. Each test file has to echo the json_encode of the following data
structure:

{
	'testLabel_1': {
		'success': true,
		'message': ''
	},
	'testLabel_2': {
		'success': false,
		'message': 'Error goes here...'
	}
}

The test files will just be executed and have to take care of the correct output by themselves.
There ist no test framework YET (!!!)

*/

?>

	</div>
</div>













