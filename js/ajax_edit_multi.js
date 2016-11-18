// define global variable
var members_checked = new Array();

// Multi-Edit Form
function edit_multi(){
	// get selected members
	members_checked.length = 0;
	var members = $('.member_list');
	for (i = 0; i < members.length; i++) { 
		if (members[i].checked) {
			members_checked.push(members[i].value);
		}
	}

	var content = `
		<h3>Zu ändernde Werte:</h3>
		<form class="ui form" method="POST">
			<table>
				<tr>
					<td width="50%">
						<div class='ui toggle checkbox'>
							<input type='checkbox' tabindex='0' class='hidden'>
							<label class='multi_edit_check' target="multi_edit_value_position" >HHC Position</label>
						</div>
					</td>
					<td width="50%">
						<select class='ui fluid dropdown multi_edit_value_position' name='Member-position'  style='width:auto' disabled>
							<option value='mitglied'>Mitglied</option>
							<option value='ressortleiter'>Ressortleiter</option>
							<option value='alumni'>Alumni</option>
							<option value='anwärter'>Anwärter</option>	
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<div class='ui toggle checkbox'>
							<input type='checkbox' tabindex='0' class='hidden'>
							<label class='multi_edit_check' target="multi_edit_value_status">HHC Status</label>
						</div>
					</td>
					<td>
						<select class='ui fluid dropdown multi_edit_value_status' name='Member-active' style='width:auto' disabled>
							<option value='0'>Aktiv</option>
							<option value='1'>Inaktiv</option>
						</select>
					</td>
				</tr>
			</table>

			<h3>Zu ändernde IDs:</h3>	
			<div class='id-list'></div>
			
		</form>
		
		<div class='popup-footer'>
			<button type='button' onclick='edit_multi_save()' class='ui blue button icon labeled'>
				<i class='save icon'></i>
				Speichern
			</button>
			<button type='button' onclick='edit_multi_delete()' class='ui red button icon labeled'>
				<i class='delete icon'></i>
				Löschen
			</button>
			<button type='button' onclick='popup_close()' class='ui button icon labeled'>
				<i class='ban icon'></i>
				Abbrechen
			</button>
		</div>
	`;

	// open popup
	popup(content, 'edit-multi', "Edit-Multi");

	// generate ID list
	var id_selectable_list = `
		<div class="ui multiple selection dropdown fluid upward"  id='id_selectable_list' style='margin:1rem auto;'>
			<div class="default text">selected IDs</div>
			<div class="menu">
	`;
	for (var i = 0; i < members_checked.length; i++) {
		$('.edit-multi .id-list').append(members_checked[i]+', ');
		id_selectable_list += "<div class='item selected' data-value='"+members_checked[i]+"'>"+members_checked[i]+"</div>";
	}
	id_selectable_list += "</div></div>";

	// activate semantic UI
	$('.ui.dropdown').dropdown('set selected',members_checked);
	$('.ui.checkbox').checkbox();
	
	$('#popup').toggleClass('modal', false);
};


// Toggle Dropdowns
$(document).on('click','.multi_edit_check',function(){
	var target = $(this).attr("target");
	$('.'+target).toggleClass('disabled');
});


// Select all checkboxes
function select_all(){
	var checkBoxes = $('.member_list');
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
};


// EDIT Multiple Members
function edit_multi_save(){
	dialog(
		'Sind Sie sicher, dass Sie ' + members_checked.length + ' Einträge ändern möchten?',
		function() {
			// OK
			popup_close();

			// $('.edit-multi .popup-content').html("<h2>Updating...</h2><div class='modal'></div>");

			var data = $(".edit-multi form").serialize()
			var form = new FormData(".edit-multi form");
			
			var myForm = document.querySelector('.edit-multi form');
			formData = new FormData(myForm);

			// $('.edit-multi .ui.form')
			// 	.api({
			// 	    url: templateDirectory+'/functions/edit/sql_edit_multi.php',
			// 	    method : 'POST',
			// 	    serializeForm: true,
			// 	    beforeSend: function(settings) {
			// 	    	console.log(settings);
			// 	    },
			// 	    onSuccess: function(data) {
			// 			console.log($.parseJSON(data));				    
			// 		}
			// 	});

			// console.log(data);
			// console.log(form.get('Member-position'));

			for(var pair of formData.entries()) {
			   console.log(pair[0]+ ', '+ pair[1]); 
			}

		  	var templateDirectory = document.getElementById('templateDirectory').value; 

			$.ajax({
		  		url: templateDirectory+'/functions/edit/sql_edit_multi.php',
			  	data: data,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					console.log($.parseJSON(data));	
					$('.edit-multi').addClass('reload-form');
					// setTimeout(function(){
					// 	$('.edit-multi .popup-content').html(`
					// 		<h2>Die Änderung war erfolgreich!</h2>
					// 		<pre>`+data+`</pre>

					// 		<button type='button' onclick='popup_close(); ajax_post()'>Schließen</close>
					// 	`);
					// }, 600);
				}
			});
		},
	function() {
		// Abbrechen
		popup_close();
	});
};


// DELETE multiple Members
function edit_multi_delete(){
	dialog(
		'Sind Sie sicher, dass Sie ' + members_checked.length + ' Einträge löschen möchten?',
		function() {
			// OK
			popup_close();
		
			$('.edit-multi .popup-content').html("<h2>Deleting...</h2><div class='modal'></div>");
			var data = new FormData();			
			data.append('ids', members_checked);
		  	var templateDirectory = document.getElementById('templateDirectory').value; 

			$.ajax({
		  		url: templateDirectory+'/functions/edit/sql_edit_multi.php',
			  	data: data,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					setTimeout(function(){
						$('.edit-multi .popup-content').html(`
							<h2>Die Änderung war erfolgreich!<h2>

							<button type='button' onclick='popup_close(); ajax_post()'>Schließen</close>
						`);
					}, 600);
				}
			});
		},
		function() {
			// Abbrechen
			popup_close();
		});
};