<?php

// I can be sure, that I am in a well suited environment
// All modules are imported, I got all wp functions and
// the auto loader is set up
// phpUnit is also loaded!
// Everything is just fine :) Now test!

$testOutput = array(
	'myFirstTest' => array(
		'success' => true,
		'message' => 'Everything alright!'
	)
);

echo json_encode($testOutput);

?>