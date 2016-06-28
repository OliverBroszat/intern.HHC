function edit(id){

	popup(null, 'edit');

	var data = new FormData();
	data.append('id', id);

  	var templateDirectory = document.getElementById('templateDirectory').value; 

	$.ajax({
  		url: templateDirectory+'/functions/edit/edit.php',
	  	data: data,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(data){
			setTimeout(function(){
				$('.popup-content').html(`
					<div id="edit">
						<form method='POST' action="`+templateDirectory+`/functions/apply/sql_apply.php">
							<h2>Eintrag bearbeiten</h2>
							<div id="popup-content"></div>
							<div id="popup-footer">
								<button type='submit'> Speichern </button> 
								<button type='button' onclick="popup_close_dialog('Wollen Sie das Fenster wirklich ohne zu speichern schließen? Ungespeicherte Änderungen gehen verloren.')"> Abbrechen </button> 
							</div>
						</form>
					</div>
				`);

				$('#edit').toggleClass("modal",false);
				$('#edit #popup-content').html(data);
			}, 600);
		}
	});
}