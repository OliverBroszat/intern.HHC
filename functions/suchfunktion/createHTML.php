<?php 
/* 
	createHTML.php 

	Nimmt die fertigen Daten von postProcess entgegen und generiert HMTL Code, welcher dann per echo ausgegeben werden kann.
*/




function createHTML($final){
	
	$entries = '';
	$number = 1;
	foreach ($final as $row) {

		// echo "<hr>Row:<br>";
		// var_dump($row);
		// echo "<br><br>";

		$entries .= "<tr><td>".getListEntryHTML($number, $row)."</td></tr>";
		$number ++;
	}
	
	$html = "
		<table class='liste search_results'>
			$entries
		</table>
	";

	return $html;
}
?>