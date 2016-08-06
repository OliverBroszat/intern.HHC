<?php
/**
 * Template Name: Suche
 *
 * @package WordPress
 * @subpackage intern-hhc
 * @since intern-hhc
 */

get_header();

?>

<div class = "outer clearfix">
	<h1>Mitgliederliste</h1>
	
<!-- Suchfeld + Suchbutton -->
<form method="POST" id="form-suche" action="<?php echo get_template_directory_uri(); ?>/functions/suchfunktion/AcceptAjax.php">
	<div id="search-box">
		<div class="ui segment search-box-cell">
			<div class="search-box-cell">
				<div class="fluid ui right action left icon input">
					<i class="search icon"></i>
				  	<input type="text" name="search_text" id='text-box' onkeyup="ajax_search_suggestions(this.value)" placeholder="Suchen" >
				  	<button class="ui primary icon button"  style="padding-left: 2rem; padding-right: 2rem;" id='start-search'>
				  		<i class="search icon"></i>
				  		<!-- Suchen -->
				  	</button>
				</div>
				<div id="suggestions"></div>
				<script>$("#suggestions").css("width", $("#text-box").css("width"));</script>
			</div>
		</div><!-- /panel -->

	</div>



	<button id="sidebar-toggle" class="search" onclick="$(this).toggleClass('show'); $('.sidebar').slideToggle(300);">Suchoptionen</button>

	<div class = "sidebar">
		
	<!-- Sortieren -->
		<div class='ui segment'>
			<h2>Sortieren nach:</h2>
			<div class="ui form">
				  <div class="fields">
				    <div class="ten wide field">
						<select class="ui fluid dropdown" name="sort" id="sort" onchange="ajax_post()">
							<!-- <option value="">Sort</option> -->

<?php
	// Sortieren
	$t_header = array(
		array('value' => 'Contact.last_name', 'name' => 'Nachname'), 
		array('value' => 'Contact.first_name', 'name' => 'Vorname'),
		array('value' => 'Contact.birth_date', 'name' => 'Alter'), 
		array('value' => 'Ressort.name', 'name' => 'Ressort'),
		array('value' => 'Member.active', 'name' => 'Status'),
		array('value' => 'Contact.id', 'name' => 'ID')
	);	


	// Print Sortieren
	foreach ($t_header as $value) {	
		echo "<option value='".$value[value]."'>".$value[name]."</option>";
	}
?>

						</select>
					</div>
					<div class="six wide field"				>
						<select class="ui fluid dropdown" name="order" id="order" onchange="ajax_post()">
							<!-- <option value="">Order</option> -->
							<option value="asc">A-Z</option>
							<option value="desc">Z-A</option>
						</select>
					</div>
				</div>
			</div>

		</div><!-- /panel -->
	

	
	<!-- Filter -->
		<div class = "ui segment filter">
				<h2>Filtern nach:</h2>


<?php

// Filter-Data
	$ressorts = res_to_array($wpdb->get_results("SELECT name FROM Ressort"));
	$positions = res_to_array($wpdb->get_results("SELECT position FROM Member"));
	$status = array('0', '1');
	$schools = res_to_array($wpdb->get_results("SELECT school FROM Study"));

	$filter = array(
		array('name' => 'ressort', 'title' => 'Ressort', 'data' => $ressorts, 'cols' => 2),
		array('name' => 'position', 'title' => 'HHC Position', 'data' => $positions, 'cols' => 2),
		array('name' => 'status', 'title' => 'HHC Status', 'data' => $status, 'cols' => 2),
		array('name' => 'uni', 'title' => 'Universität', 'data' => $schools, 'cols' => 1)
	);


	foreach ($filter as $category) {
		$cols = $category['cols'];
		$data = $category['data'];
		$name = $category['name'];

		echo("
			<table>
				<tr>
					<th colspan='$cols'>
						".$category['title']."
					</th>
				</tr>
				
					
		");

		for ($i=0; $i < $cols; $i++) { 		
			$count = count($data)/$cols;
			// Problem: doppelte Einträge, wenn $count einen Rest hat

			$width = 100/$cols;
			echo "<td style='width:".$width."%'><table>";
			
			for ($j=0; $j < $count; $j++) { 					
				$value = $data[$j + $i * $count];
				echo "
					<tr>
						<td width='1px'>
							<div class='ui checkbox'>
						    	<input 
									type='checkbox' 
									tabindex='0' 
									name='f_".$name."_list[]'
									value='$value' 
									id='f_".$name."_".$value."'
									class='hidden filtercheckbox_".$name."'
								>
						      <label>".uppercase(bool_to_lbl($value))."</label>
						    </div>
						</td>

					</tr>
				";
			}
			echo "</td></table>";
		}
		echo "</tr></table>";
	}

?>

			
				<input type="hidden" disabled name="templateDirectory" id="templateDirectory" value="<?php echo get_template_directory_uri(); ?>">

				<button type='button' class='fluid ui labeled icon button' onclick="ajax_post();">
					<i class="refresh icon"></i>
					Aktualisieren
				</button>
		</div><!-- /panel -->
	</div><!-- /sidebar -->
	</form>

	<main class="container">
		<div class="ui segment actions">					

			<button type='button' class='ui labeled icon button' id='new-entry' value='new' onclick="edit('');">
				<i class="file outline icon"></i>
				Neu
			</button>

			<button type='button' class='ui labeled icon button' onclick='edit_multi()'>
				<i class="edit icon"></i>
				Edit selected
			</button>

			<button type='button' class='ui labeled icon button' onclick='select_all()'>
				<i class="checkmark box icon"></i>
				Select/Deselect all
			</button>

		</div><!-- /panel -->

<!--  Suchergebnisse -->
		<div class='ui segment'>
			
				<h2 id='search-results-title'>Suchergebnisse (0)</h2>
				<div id='list-container'>
					<!--<div class="modal"> Place at bottom of page </div>-->
					<?php echo $html ?>
				</div>
			
		</div><!-- /panel -->
	</main>
	
</div><!-- /outer -->


<!-- Semantic UI -->
<script>
	$('.ui.checkbox').checkbox();
	$('.ui.dropdown').dropdown();
</script>

<!-- Import ajax_post() function -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search.js"></script>

<!-- Call AJAX Search on page load -->
<script>window.onload=ajax_post;</script>

<!-- Call AJAX Search on #form-suche submit -->
<script>
	$("#form-suche").submit(function(e){
	    e.preventDefault();
	    $("#start-search").focus();
	    ajax_post();

	});
</script>

<!-- AJAX Search Suggestions -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_search_suggestions.js"></script>

 <!-- AJAX Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/ajax_edit.js"></script> 

<!-- Multi-Edit -->
<script src="<?php echo get_template_directory_uri(); ?>/js/edit_multi.js"></script> 

<!-- Expand Detail Content -->
<script>
	function expand_content(value){
		$('#slide_content_show_detail_'+value).slideToggle(300);
	}
</script>



<?php get_footer(); ?>