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
		<h2>Multi-Edit</h2>
		(macht noch nichts)

		<h3>Zu ändernde Werte:</h3>
		<form>
			<table>
				<tr>
					<td>
						<div class='ui toggle checkbox'>
							<input type='checkbox' tabindex='0' class='hidden'>
							<label class='multi_edit_check' target='multi_edit_value_position'>HHC Position</label>
						</div>
					</td>
					<td>
						<select class='ui fluid dropdown multi_edit_value' id='multi_edit_value_position' name='multi_edit_value'  style='width:auto' disabled='disabled'>
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
							<label class='multi_edit_check' value='multi_edit_value_status'>HHC Status</label>
						</div>
					</td>
					<td>
						<select class='ui fluid dropdown multi_edit_value' id='multi_edit_value_status' name='multi_edit_value' style='width:auto'>
							<option value='aktiv'>Aktiv</option>
							<option value='inaktiv'>Inaktiv</option>
						</select>
					</td>
				</tr>
			</table>

			<h3>Zu ändernde IDs:</h3>
			
			<div class='id-list'></div>
			
		</form>
		
		<div id='popup-footer'>
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
	popup(content, 'edit-multi');

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
	// append ID list
	$('.edit-multi .popup-content form').append(id_selectable_list);

	// activate semantic UI
	$('.ui.dropdown').dropdown('set selected',members_checked);
	$('.ui.checkbox').checkbox();
	
	$('#popup').toggleClass('modal', false);
};


// Toggle disabled
$(document).on('click','.multi_edit_check',function(){
	
	var id = $('this').prop('target');
	var x = $('this');
	// alert(x.classList);
	$('#'+id).attr('disabled',!this.checked);
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

			$('.edit-multi .popup-content').html("<h2>Updating...</h2><div class='modal'></div>");
			var form = $(".edit-multi form");
			var data = new FormData(form);
			
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
							<h2>Die Änderung war erfolgreich!</h2>
							<pre>`+data+`</pre>

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