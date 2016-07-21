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
				
				$('.edit .popup-content').html(data);
				placeholder_color();

				// Center Popup again after loading. Needs some kind of delay (?)
				// setTimeout(function(){
				// 	$(".edit .popup-content-outer").center();
				// }, 0);
				
				// $('#edit-form').validate();
				
				// check if image is uploaded
				if ($(".edit-image-image").find('a').length) {
					$('#edit-upload-image').hide();
					$('#edit-delete-image').show();
				}

			}, 0);
		}
	});
}



// SAVE
$(document).on('click', '#edit-save', function(event){
	event.preventDefault();
	var id = $(this).val();
	$('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "edit").val(id));
    $('#edit-form').submit();
});


// DELETE
$(document).on('click', '#edit-delete', function(event){
	event.preventDefault();
	
	var id = $(this).val();

	dialog(
        'Sind Sie sicher, dass Sie diesen Datensatz löschen möchten? Die Löschung kann nicht rückgangig gemacht werden!',
        function() {
            // Schließe den Dialog
            popup_close();
            // submit form
            setTimeout(function() {
	            $('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "delete").val(id));
	            $('#edit-form').submit();
	        }, 200);
        },
        function() {
            // Schließe nur das Popup
            popup_close();
        }
    );
});


// SUBMIT FORM
$( document ).on('submit', '#edit-form', function( event ) {
	event.preventDefault();
	
	var form = $( this )
	var url = form.attr( "action" );

	$('.edit .popup-content').addClass('modal');
	// $('.edit .popup-content-outer').center();

	
	// Send the data using post
	$.post( url, form.serialize(), function( data ) {
		setTimeout(function() {
			// Debug Output
			$(".edit .popup-content").html(data);
			$('.edit .popup-content').removeClass('modal');
			// $('.edit .popup-content-outer').center();
		}, 100);
	});

});



// IMAGE
$(document).on('click', '#edit-delete-image', function(){
	$('.edit-image-image a').remove();
	$('#edit-delete-image').hide();
	$('#edit-upload-image').show();

});

$(document).on('click', '#edit-upload-image', function(){

});