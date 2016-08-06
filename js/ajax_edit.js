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
	$('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "crud-id").val(id));
	$('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "crud-mode").val('edit'));
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
   
            setTimeout(function() {
	           // append ID
	            $('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "crud-id").val(id));
	            $('#edit-form').append($("<input>").attr("type", "hidden").attr("name", "crud-mode").val('delete'));
	           // submit form
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
$( document ).on('submit', '#edit-form', function(event) {	
	event.preventDefault();
	
	var form = $(this),
		data = new FormData(this);

	$('.edit .popup-content').addClass('modal');

	jQuery.ajax({
	    url: form.attr("action"),
        type: form.attr("method"),
        data: data,
        processData: false,
        contentType: false,
        success: function (result, status)
        {
        	setTimeout(function() {
				// Debug Output
				$(".edit .popup-content").html(result);
				$('.edit .popup-content').removeClass('modal');
			}, 100);
        },
        error: function (xhr, desc, err)
        {
        	$(".edit .popup-content").html('xhr: ' + xhr + '<br>desc: ' + desc + '<br>err: ' + err);
        	$('.edit .popup-content').removeClass('modal');
        }
	});
});


// IMAGE
$(document).on('click', '#edit-delete-image', function(){
	$('.edit-image-image a').remove();
	$('#edit-delete-image').hide();
	$('#edit-upload-image').show();

});