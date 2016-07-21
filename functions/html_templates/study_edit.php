<?php 
// Load WP-Functions
$localhost = array( '127.0.0.1', '::1' ); 
$root = realpath($_SERVER["DOCUMENT_ROOT"]); 
if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){ 
    $root = realpath($_SERVER["CONTEXT_DOCUMENT_ROOT"]).'/wordpress'; 
} 
require_once("$root/wp-load.php");

require_once(get_template_directory().'/functions/html_templates/userdata.php');

?>

<table class='form'>
	<tr>
		<td style='vertical-align: top;'>Status</td>
		<td style='vertical-align: top;'>
			<select name='Study-status[]' id='study_status_%%FULL-ID%%'>
				<option disabled selected value class='placeholder'>Status</option>
				<option value='active'>Aktiv</option>
				<option value='done'>Abgeschlossen</option>
				<option value='cancelled'>Abgebrochen</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Abschluss</td>
		<td>
			<select id='degree-%%FULL-ID%%' name='Study-degree[]' onChange='showDiv(this);'>
				
				<?php 
					$degrees = array(
						array('value'=>'b sc', 'data'=>'b_sc', 'title'=>'Bachelor of Science'),
						array('value'=>'m sc', 'data'=>'m_sc', 'title'=>'Master of Science'),
						array('value'=>'b s', 'data'=>'b_s', 'title'=>'Bachelor of Arts'),
						array('value'=>'m a', 'data'=>'m_a', 'title'=>'Master of Arts'),
						array('value'=>'exam', 'data'=>'exam', 'title'=>'Staatsexamen'),
						array('value'=>'diplom', 'data'=>'diplom', 'title'=>'Diplom'),
						array('value'=>'other', 'data'=>'other', 'title'=>'anderer Abschluss...'),
					);

					$resl .= "<option disabled selected value class='placeholder'>Abschluss</option>";

					foreach ($degrees as $value) {
						$resl .= "<option value='".$value['value']."' %%DATA-".$value['data']."%% >".$value['title']."</option>";
					}

					echo "$resl";

				?>
			</select>

		</td>
		<td>
			<input id='hidden_div-degree-%%FULL-ID%%' type='text' name='Study-degree[]' placeholder='anderer Abschluss...' value='%%DATA-other_extra%%' disabled style='display:none;'/>
		</td>
	</tr>
	<tr>
		<td>Fach</td>
		<td>
			<input type='text' name='Study-course[]' placeholder='Fach' value='%%DATA-course%%'/>
		</td>
		<td>
			<input type='text' name='Study-focus[]' placeholder='(Schwerpunkt)' value='%%DATA-focus%%'/>
		</td>
	</tr>
	<tr>
		<td width ='20%' style='vertical-align: top;'>Universität</td>
		<td width='40%' style='vertical-align: top;'>
			<select id='school-%%FULL-ID%%' name='Study-school[]' onChange='showDiv(this);'>
				<option disabled selected value class='placeholder'>Universität</option>
				<option value='Heinrich-Heine-Universität' %%DATA-checked-HHU%%>Heinrich-Heine-Universität</option>
				<option value='FH Düsseldorf' %%DATA-checked-FH%%>FH Düsseldorf</option>
				<option value='Universität Duisburg-Essen' %%DATA-checked-DUE%%>Universität Duisburg-Essen</option>
				<option value='Universität Köln' %%DATA-checked-KOELN%%>Universität Köln</option>
				<option value='FOM' %%DATA-checked-FOM%%>FOM</option>
				<option value='Bergische Universität Wuppertal' %%DATA-checked-WUPPERTAL%%>Bergische Universität Wuppertal</option>
				<option value='other' %%DATA-checked-OTHER%%>andere Universität...</option>
			</select>
		</td>
		<td width='40%' style='vertical-align:top;'>
			<input id='hidden_div-school-%%FULL-ID%%' type='text' name='Study-school[]' placeholder='andere Hochschule...' value='%%DATA-other-text%%' disabled style='display:none;'/>
			<br>
		</td>
	</tr>
	<tr>
		<td>Beginn / Ende</td>
		<td style='vertical-align:top;'><input type='date' name='Study-start[]' value='%%DATA-start%%'/></td>
		<td style='vertical-align:top;'><input type='date' name='Study-end[]' value='%%DATA-end%%'/></td>
	</tr>
	<tr>
	</tr>
</table>