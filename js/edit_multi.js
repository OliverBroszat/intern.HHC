function edit_multi(){

	var members = $('.member_list');
	var members_checked = new Array();
	for (i = 0; i < members.length; i++) { 
		if (members[i].checked) {
			members_checked.push(members[i].value);
		}
	}

	var content = `
		<h2>Multi-Edit</h2>
		(macht noch nichts)

		<h3>Zu ändernde Werte:</h3>
		
		<table>
			<tr>
				<td>
					<input type='checkbox' class='multi_edit_check' id='multi_edit_check_position' value='multi_edit_value_position'>
					<label for='multi_edit_check_position'>HHC Position</label>
				</td>
				<td>
					<select name='multi_edit_value' class='multi_edit_value' id='multi_edit_value_position' style='width:auto' disabled='disabled'>
						<option value='mitglied'>Mitglied</option>
						<option value='ressortleiter'>Ressortleiter</option>
						<option value='alumni'>Alumni</option>
						<option value='anwärter'>Anwärter</option>	
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<input type='checkbox' class='multi_edit_check' id='multi_edit_check_status' value='multi_edit_value_status'>
					<label for='multi_edit_check_status'>HHC Status</label>
				</td>
				<td>
					<select name='multi_edit_value' class='multi_edit_value' id='multi_edit_value_status' style='width:auto' disabled='disabled'>
						<option value='aktiv'>Aktiv</option>
						<option value='inaktiv'>Inaktiv</option>
					</select>
				</td>
			</tr>
		</table>

		<h3>Zu ändernde IDs:</h3>
		
		<div class='id-list'></div>
		
		<div id='popup-footer'>
			<button type='button' onclick='edit_multi_save()'>Ändern!</buttun>
			<button type='button' onclick='popup_close()'>Abbrechen</button>
		</div>
	`;

	popup(content, 'edit-multi');

	for (var i = 0; i < members_checked.length; i++) {
		$('.edit-multi .id-list').append(members_checked[i]+', ');
	}
	
	$('#popup').toggleClass('modal', false);

	// Center Popup again after loading. Needs some kind of delay (?)
	setTimeout(function(){
		$(".edit-multi .popup-content-outer").center();
	}, 0);
};


// Toggle disabled
$(document).on('click','.multi_edit_check',function(){

	var id = this.value;

	$('#'+id).attr('disabled',!this.checked);

});


// Select all checkboxes
function select_all(){
	var checkBoxes = $('.member_list');
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
};


function edit_multi_save(){
	$('.edit-multi .popup-content').html("<h2>Updating...</h2><div class='modal'></div>");
	$('.edit-multi .popup-content-outer').center();

	var data = new FormData();
	
	// data.append('id', id);

  	var templateDirectory = document.getElementById('templateDirectory').value; 

	$.ajax({
  		url: templateDirectory+'/functions/edit/sql_edit.php',
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
			$('.edit-multi .popup-content-outer').center();
		}
	});

}