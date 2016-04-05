<!DOCTYPE html>
<html>
<head>
	<title>Suche-Schema</title>
	<style type="text/css">
		html{
			background-color: #eee;
		}
		body{
			width: 100%;
			max-width: 960px;
			padding: 1.5rem;
			margin: 2rem auto;
			background-color: #fff;
		}
	</style>
</head>
<body>

<?php 
/*
	Hiermit sollen die Suchbegriffe und die Filter zu einem SQL Statement umgewandelt werden.
	Der fertige String wird dafür Stück für Stück zusammegesetzt.
*/


// Funktion, um für Testzwecke einfache Überschriften mit Absätzen auszugeben
function title($string){
	echo "<br><br><b>$string: </b>";
}

// Array mit den Suchbegriffen
$search_words = array('alpha', 'beta');
title('Search Words');
foreach ($search_words as $value) {
	echo "$value, ";
}



// Mappe die Suchworte auf Strings der länge 1
$suchworte = array();
for ($i = 0; $i < count($search_words); $i++) {
	$suchworte[$i] = chr(ord(x) + $i);
}


title('Suchworte');
foreach ($suchworte as $value) {
	echo "$value, ";
}

// Array mit den Spalten, in denen nach den Suchbegriffen gesucht werden soll
$spalten = array('id', 'ressort', 'school');
title('Spalten');
foreach ($spalten as $value) {
	echo "$value, ";
}

// Array mit den Spalten, nach denen gefiltert werden soll, und den Werten, nach denen die entsprechenden Spalten gefiltert werden sollen
$filter = array(
	'ressort' => array('hr', 'marketing'),
	'school' => array('hhu', 'vhs'),
);
title('Filter');
var_dump($filter);

// Bis hierhin müssen alle Werte aus dem Formular oder der Datenbank eingelesen werden bzw. im Code vordefiniert werden
echo "<hr>";
// Ab hier soll anhand dieser Werte das SQL-Statement zusammengesetzt werden

// SQL SELECT
$select = "SELECT ";
for ($i=0; $i < sizeof($spalten); $i++) { 
	$select .= $spalten[$i];
	if ($i < (sizeof($spalten) - 1)) {
		$select .= ", ";
	}
}
title('SQL Select');
var_dump($select);


// SQL WHERE
$where = "WHERE";

// title('SQL Where');
// var_dump($where); 


// Eine seperate Auflistung der Spalten, nach denen gefiltert werden soll
$filter_keys = array_keys($filter);
title('Filter Keys');
var_dump($filter_keys);


// SQL WHERE Statement, um die Filter zu berücksichtigen.
$w_filter = "";
$j = 0;
foreach ($filter_keys as $key) {	
	$w_filter .= "(";
	$i = 0;
	foreach ($filter[$key] as $value) {
		$w_filter .= "$key = $value";

		if ($i < (sizeof($filter[$key]) - 1)) {
			$w_filter .= " OR  ";
		} 
		else{
			$w_filter .= ")";
		}
		$i ++;
	}
	if ($j < (sizeof($filter_keys) - 1)) {
		$w_filter .= " AND ";
	}
	$j ++;
}
title('Where Filter');
var_dump($w_filter); 

/*
	Definieren eines eigenen Zahlensystems mit den Suchbegriffen (x, y) als Ziffern.
	Diese werden dann auf ein Zahlensystem von 0 bis x abgebildet, um damit rechnen zu können.
	Dadurch soll folgende Operation möglich sein:

	000		+1		
	00x 	+1
	00y 	+1
	0x0 	+1
	0xx 	+1
	0xy		+1
	0y0 	+1
	...
*/

// Auflistung Suchbegriffe in der Art 0xy...
$custom_digits = '0';
 foreach ($suchworte as $value) {
   $custom_digits .= $value;
}
title('Custom Digits');
echo $custom_digits;


// Arry mit maximaler Anzahl an Ziffern, auf die die Suchworte abgebildet werden können (0 bis x: 36 Ziffern)
$digits_list = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

// Reduzierung der Ziffern aus digits_list auf die Anzahl der Suchbegriffen
$base_n_digits = '';
for ($i=0; $i < strlen($custom_digits); $i++) { 
	$base_n_digits .= $digits_list[$i];
}
title('Custom Base');
echo $base_n_digits;

// Funktion, um Dezimalzahlen in das eigene Zahlensystem umzuwandeln
function dec_to_custom($n) {
    global $custom_digits;
    global $base_n_digits;
    return strtr(base_convert($n, 10, strlen($base_n_digits)), $base_n_digits, $custom_digits);
}

// Rückumwandlung
function custom_to_dec($n) {
    global $custom_digits;
    global $base_n_digits;
    $base_n = strtr($n, $custom_digits, $base_n_digits);
    return base_convert($base_n, strlen($base_n_digits), 10);
}


$length = pow(strlen($custom_digits), sizeof($spalten));

title('Länge');
echo $length;
$ze = array(custom_to_dec(0));

for ($i = 1; $i < $length; $i++) {
	$ze[$i] = $ze[$i - 1] + custom_to_dec(1);
}

$folge = array();
$start = 13;
for ($i = $start; $i < sizeof($ze); $i++) {
 	array_push($folge, dec_to_custom($ze[$i]));
}
title('Folge');
var_dump($folge);

$char_list = array();
$i = 0;
$j = 0;
foreach ($folge as $str) {
	$chars = str_split($str);
	foreach($chars as $char){
    	$char_list[$i][$j]= $char;
    	$j ++;
	}
	$i ++;
}

title('Char List');
var_dump($char_list);
echo "<br><br>";

$ausdruck = '';
$i = 0;
foreach ($spalten as $value) {
	$ausdruck .= "$value = %s";
	
	if($i < (sizeof($spalten) - 1)){
		$ausdruck .= " AND ";
	}

	$i ++;
}

$length = pow(sizeof($suchworte), sizeof($spalten));
$i = 0;
$statement = '';
foreach ($char_list as $value) {
	if(!(in_array('0', $value))){
		$statement .= "(";
		$statement .= vsprintf($ausdruck, $value);
		
		if($i < ($length - 1)){
			$statement .= ") OR";
		} else {
			$statement .= ")";
		}

		$i ++;
		$statement .= "<br>";
	}
}
title('Platzhalter');
echo "<br>";
echo $statement;


// Ersetze x, y,... mit längeren strings
$final_statement = str_replace($suchworte, $search_words, $statement);

title('Where Suchbegriffe');
echo "<br>";
echo $final_statement;


// Füge alles zusammen
$final_sql = "$select <br> (...) <br> WHERE <br>$w_filter <br>AND (<br> $final_statement)";

title('Combined');
echo "<br>";
echo $final_sql;


echo "<br><br>";


?>