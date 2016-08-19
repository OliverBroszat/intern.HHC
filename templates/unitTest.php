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


/**
 * dirToArray
 * 
 * Recursively generates an array that represents the file/dir structure from a given root directory
 * 
 * @param dir String The directory where to start from
 * @param depth Int This is a counter to cap the recursion depth. If dirToArray is called with a depth less than or equal
 * to 0, the function will return null. By default the depth is 5
 * 
*/
function dirToArray($dir, $depth=5) {

	if ($depth < 1) {
		return null;
	}
   
	$result = array();

	$cdir = scandir($dir);
	foreach ($cdir as $key => $value) {
		if (!in_array($value,array(".",".."))) {
			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
				$result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value, $depth-1);
			}
			else {
				$result[] = $value;
			}
		}
	}
	return $result;

}

function getTestObject($moduleName, $fileName) {
	global $_TEMPLATE_DIRECTORY;
	$fullPathToFile = $_TEMPLATE_DIRECTORY . '/test/Modules/' . $moduleName . '/' . $fileName;
	include_once($fullPathToFile);
	$className = explode('.', $fileName)[0];
	$testObject = new $className();
	return $testObject;
}

$rootTestDirectory = $_TEMPLATE_DIRECTORY . '/test/Modules';
$rootTestUrl = $_TEMPLATE_URL . '/test/Modules';

$moduleList = dirToArray($rootTestDirectory, $depth=2);

$currentTestID = 0;
$testData = array();
foreach ($moduleList as $moduleName => $testFiles) {
	$currentModuleData = array(
		'module-name' => $moduleName,
		'module-info' => '-',
		'module-tests' => null
	);
	$moduleTests = array();
	foreach ($testFiles as $testFile) {
		$testObject = getTestObject($moduleName, $testFile);
		$currentTest = array(
			'test-id' => $currentTestID,
			'test-name' => get_class($testObject),
			'test-info' => $testObject->getInformation(),
			'test-url' => $rootTestUrl . '/' . $moduleName . '/' . $testFile
		);
		array_push($moduleTests, $currentTest);
		$currentTestID++;
	}
	$currentModuleData['module-tests'] = $moduleTests;
	array_push($testData, $currentModuleData);
}
$fullData = array(
	'modules' => $testData
);

echo $PublicMustacheEngine->render('tmpl_Testcenter', $fullData);

?>

	</div>
</div>













