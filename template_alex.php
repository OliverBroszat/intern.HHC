<?php
/**
 * Template Name: Template Alex
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen Child
 * @since Twenty Fourteen 1.0
 */

if (!is_user_logged_in()) { auth_redirect(); }
get_header();
?>

<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
			
	// HIER GEHT'S LOS
	echo '<h1>Hallo, Alex!</h1><br>';

	$array = $wpdb->get_results('SELECT * FROM TEST_USERS;');

			// Version 1
			// Mit var_dump() lässt sich der 'rohe' Inhalt einer Variablen ausgeben
			//var_dump($array);

			// Version 2
			// Mittels foreach() kann ein Array komfortabel durchlaufen werden
			//foreach($array as $row) {
			//	var_dump($row);
			//	echo '<br></br>';
			//}
			//echo '<h2>ich bin aus PHP gemacht</h2>';

			// Version 3
			// Eine einfache Tabelle
			//$vorname = $array[0]->first_name;
			//$nachname = $array[0]->last_name;
			//$studium = $array[0]->studium;
			//echo "<table>
			//<tr>
			//<td><b>Vorname</b></td> <td><b>Nachname</b></td> <td><b>Studium</b></td>
			//	</tr>
			//	<tr>
			//		<td>$vorname</td> <td>$nachname</td> <td>$studium</td>
			//	</tr>
			//	<tr>
			//		<td>Tim</td> <td>Schrills</td> <td>Bachelor</td>
			//	</tr>
			//</table>";

			// Version 4
			// Dynamisch erzeugte Tabelle
			$headline = '<td><b>Vorname</b></td> <td><b>Nachname</b></td> <td><b>Studium</b></td>';
			echo '<table>';
			echo '<tr>' . $headline . '</tr>';
			foreach( $array as $row ) {
				echo "<tr><td>$row->first_name</td> <td>$row->last_name</td> <td>$row->studium</td></tr>";
			}
			echo '</table>';
			echo '<br>';
			echo get_query_var( 'wert', '-1' );
			?>

		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();