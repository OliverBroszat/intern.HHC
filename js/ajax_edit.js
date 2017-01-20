function edit(id){

	popup(null, "edit", "Eintrag bearbeiten");

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
		
				// check image buttons
				if ($(".edit-image-image a").attr('href') != '#') {
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
		success: function (result, status) {
			popup_close();
			ajax_post();
		},
		error: function (xhr, desc, err) {
			$(".edit .popup-content").html('xhr: ' + xhr + '<br>desc: ' + desc + '<br>err: ' + err);
			$('.edit .popup-content').removeClass('modal');
		}
	});
});


// Swap Upload-Remove Image Button
$(document).on('click', '#edit-delete-image', function(){
	$(".edit-image-image a").attr('href', '#');
	imagePlaceholder();

	$('#edit-delete-image').hide();
	$('#edit-upload-image').show();
	$('#edit-upload-image').val('');
});

// Image Thumbnail
function loadFile(event) {
	var src = URL.createObjectURL(event.target.files[0]);
	
	$(".edit-image-image").html(`
		<a href="`+src+`" target="_blank" onclick="image_popup(this, event)">
			<img src="`+src+`" class="profile-picture" alt="Profilbild">
		</a>
	`);
	
	$('#edit-upload-image').hide();
	$('#edit-delete-image').show();
};

// Image Placeholder
function imagePlaceholder() {
	var target = $(".edit-image-image a");
	if (target.attr('href') == '#') {
		var gender = $("#edit [name='Contact-prefix']").val();
		if (gender == 'Herr') {
			var src = "http://localhost/wordpress/wp-content/themes/intern-hhc/resources/images/profile_placeholder_male.png";
		}
		else {
			var src = "http://localhost/wordpress/wp-content/themes/intern-hhc/resources/images/profile_placeholder_female.png";
		}
		target.html("<img src='"+src+"' class='profile-picture'>")	
	}
};

$(document).on("change", "#edit [name='Contact-prefix']", function() {
	imagePlaceholder();
});

function newMemberTestData() {
	$("select[name='Contact-prefix']").val('Herr');
	$("input[name='Contact-first_name']").val('Max');
	$("input[name='Contact-last_name']").val('0000 Mustermann');
	$("input[name='Contact-birth_date']").val('2000-01-01');
	$("input[name='Contact-skype_name']").val('Test');
	
	$("input[name='Mail-description[]']").val('Test');
	$("input[name='Mail-address[]']").val('test@test.de');
	
	$("input[name='Phone-description[]']").val('Test');
	$("input[name='Phone-number[]']").val('12345678');
	
	$("input[name='Address-description[]']").val('Test');
	$("input[name='Address-street[]']").val('Test');
	$("input[name='Address-number[]']").val('123');
	$("input[name='Address-addr_extra[]']").val('Test');
	$("input[name='Address-postal[]']").val('12345');
	$("input[name='Address-city[]']").val('Test');

	$("select[name='Study-status[]']").val('active');
	$("select[name='Study-degree[]']").val('b sc');
	$("input[name='Study-course[]']").val('Test');
	$("select[name='Study-school[]']").val('Heinrich-Heine-Universität');
	$("input[name='Study-focus[]']").val('Test');
	$("input[name='Study-start[]']").val('2001-01-01');
	$("input[name='Study-end[]']").val('2002-01-01');
	
	$("select[name='Member-active']").val('1');
	$("select[name='Member-ressort']").val('12');
	$("select[name='Member-position']").val('mitglied');
	$("input[name='Member-joined']").val('2001-01-01');
	$("input[name='Member-left']").val('2002-01-01');

	$("textarea[name='Contact-comment']").val('Test');

	placeholder_color();
}